<?php
/**
 * MIT License
 *
 * Copyright (c) 2017 Frisks
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */

namespace Kori\KingdomServerBundle\Tests\Units\Service;

use atoum\test;
use Kori\KingdomServerBundle\Entity\Avatar;
use Kori\KingdomServerBundle\Entity\BattleLog;
use Kori\KingdomServerBundle\Entity\Consumable;
use Kori\KingdomServerBundle\Entity\ConsumablesEffect;
use Kori\KingdomServerBundle\Service\EffectManager;
use Kori\KingdomServerBundle\Service\RuleManager;
use Kori\KingdomServerBundle\Service\Server as TestedModel;
use Kori\KingdomServerBundle\Tests\Rules\MissingRaid;
use Kori\KingdomServerBundle\Tests\Rules\OrangePot;
use Kori\KingdomServerBundle\Tests\Rules\RedPot;

/**
 * Class Server
 * @package Kori\KingdomServerBundle\Tests\Units\Service
 */
class Server extends test
{

    public function testConsume()
    {
        $avatar = new Avatar();
        $avatar->setMaxHealth(100);
        $avatar->setHealth(1);

        $item = new Consumable();
        $effect = new ConsumablesEffect();
        $effect->setType(1);
        $effect->setValue(50);

        $effect2 = new ConsumablesEffect();
        $effect2->setType(0);
        $effect2->setValue(50);

        $item->addEffect($effect);
        $item->addEffect($effect2);

        $rule = new RedPot();
        $rule2 = new OrangePot();
        $effectManager = new EffectManager();
        $effectManager->addEffectRule($rule);

        $this
            ->given($entityManager = new \mock\Doctrine\ORM\EntityManager())
            ->and($dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcher())
            ->and($server = new TestedModel($entityManager, 1, 7, []))
            ->and($server->setEffectManager($effectManager))
            ->and($server->setDispatcher($dispatcher))
            ->when($result = $server->consume($avatar, $item))
            ->then(
                $this->boolean($result)->isTrue("Consuming should succeed because avatar is not away")
                    ->and($this->integer($avatar->getHealth())->isEqualTo(51, "The red pot effect should have added 50 hp"))
                    ->and($this->mock($dispatcher)->call('dispatch')->exactly(1))
            )
           ->and($effectManager->addEffectRule($rule2))
            ->when($result = $server->consume($avatar, $item))
            ->then(
                $this->boolean($result)->isTrue("Consuming should succeed because avatar is not away")
                    ->and($this->integer($avatar->getHealth())->isEqualTo(100, "The red pot effect should have added 50 hp to hit initial max because it is the first effect"))
                    ->and($this->integer($avatar->getMaxHealth())->isEqualTo(200, "The orange pot effect should have added 100 max hp to produce 200 max hp"))
                    ->and($this->mock($dispatcher)->call('dispatch')->exactly(2))
            )
            ->and($avatar->setBattleLog(new BattleLog()))
            ->when($result = $server->consume($avatar, $item))
            ->then(
                $this->boolean($result)->isFalse("Consuming should fail because avatar is away")
            )
            ->when($result = $server->consume($avatar, $item, true))
            ->then(
                $this->boolean($result)->isTrue("Consuming should succeed because avatar is away but ignoring flag is set")
                    ->and($this->integer($avatar->getHealth())->isEqualTo(150, "The red pot effect should have added 50 hp"))
                    ->and($this->integer($avatar->getMaxHealth())->isEqualTo(300, "The orange pot effect should have added 100 max hp to produce 300 max hp"))
                    ->and($this->mock($dispatcher)->call('dispatch')->exactly(3))
            )
        ;
    }

    public function testBuild()
    {

        $ruleManager = new RuleManager();
        $ruleManager->addBuildRule(new \Kori\KingdomServerBundle\Rules\Build\Basic());

        $this
            ->given($entityManager = new \mock\Doctrine\ORM\EntityManager())
            ->and($level = new \mock\Kori\KingdomServerBundle\Entity\BuildingLevel())
            ->and($town = new \mock\Kori\KingdomServerBundle\Entity\Town())
            ->and($dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcher())
            ->and($server = new TestedModel($entityManager, 1, 7, []))
            ->and($server->setRuleManager($ruleManager))
            ->then(
                $this->exception(function() use($server, $town, $level) {
                    $server->build($town, $level, 2);
                })->hasMessage("Build rules are empty", "Server should alert that there are no build rules.")
            )
            ->and($server = new TestedModel($entityManager, 1, 7, ["build" => ["basic"]]))
            ->and($server->setRuleManager($ruleManager))
            ->and($server->setDispatcher($dispatcher))
            ->and($this->calling($town)->canBuildPosition = function() {
                return false;
            })
            ->and($this->calling($level)->fulfillBuildingRequirements = function() {
                return false;
            })
            ->and($this->calling($level)->fulfillResourceRequirements = function() {
                return false;
            })
            ->when($result = $server->build($town, $level, 2))
            ->then(
                $this->boolean($result)->isFalse("Should fail to build because can build position returned false")
            )
            ->and($this->calling($town)->canBuildPosition = function() {
                return true;
            })
            ->when($result = $server->build($town, $level, 2))
            ->then(
                $this->boolean($result)->isFalse("Should fail to build because can building requirements failed returned false")
            )
            ->and($this->calling($level)->fulfillBuildingRequirements = function() {
                return true;
            })
            ->when($result = $server->build($town, $level, 2))
            ->then(
                $this->boolean($result)->isFalse("Should fail to build because can resource requirements failed returned false")
            )
            ->and($this->calling($level)->fulfillResourceRequirements = function() {
                return true;
            })
            ->when($result = $server->build($town, $level, 2))
            ->then(
                $this->boolean($result)->isTrue("All basic rule component has passed")
                    ->and($this->mock($entityManager)->call('persist')->exactly(1))
                    ->and($this->mock($entityManager)->call('flush')->exactly(1))
                    ->and($this->mock($dispatcher)->call('dispatch')->exactly(1))
            )
        ;
    }

    public function testMissingRule()
    {
        $ruleManager = new RuleManager();
        $ruleManager->addBuildRule(new \Kori\KingdomServerBundle\Rules\Build\Basic());
        $ruleManager->addAttackRule(new MissingRaid());
        $ruleManager->addAttackRule(new \Kori\KingdomServerBundle\Rules\Attack\NoBattle());
        $ruleManager->addInfluenceRule(new \Kori\KingdomServerBundle\Rules\Influence\Standard());

        $this
            ->given($entityManager = new \mock\Doctrine\ORM\EntityManager())
            ->and($server = new TestedModel($entityManager, 1, 7, ["build" => ["basic"], "attack" => ["missingraid"], "influence" => "standard"]))
            ->when($server->setRuleManager($ruleManager))->error("Server attack rules does not cover all battle types")->exists()
            ->and($server = new TestedModel($entityManager, 1, 7, ["build" => ["basic"], "attack" => ["missingraid", "nobattle"], "influence" => "standard"]))
            ->when($server->setRuleManager($ruleManager))->error("Server attack rules has multiple coverage of same type")->exists()
        ;
    }
}
