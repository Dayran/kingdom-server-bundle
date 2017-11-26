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
use Kori\KingdomServerBundle\Service\Server as TestedModel;
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
            ->and($server = new TestedModel($entityManager, 1, 7, []))
            ->and($server->setEffectManager($effectManager))
            ->when($result = $server->consume($avatar, $item))
            ->then(
                $this->boolean($result)->isTrue("Consuming should succeed because avatar is not away")
                    ->and($this->integer($avatar->getHealth())->isEqualTo(51, "The red pot effect should have added 50 hp"))
            )
           ->and($effectManager->addEffectRule($rule2))
            ->when($result = $server->consume($avatar, $item))
            ->then(
                $this->boolean($result)->isTrue("Consuming should succeed because avatar is not away")
                    ->and($this->integer($avatar->getHealth())->isEqualTo(100, "The red pot effect should have added 50 hp to hit initial max because it is the first effect"))
                    ->and($this->integer($avatar->getMaxHealth())->isEqualTo(200, "The orange pot effect should have added 100 max hp to produce 200 max hp"))
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
            )
        ;
    }
}
