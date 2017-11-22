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

namespace Kori\KingdomServerBundle\Tests\Units\Command;

use atoum\test;
use Kori\KingdomServerBundle\Activity\Standard\ProcessBattle;
use Kori\KingdomServerBundle\Command\CronCommand as TestedCommand;
use Kori\KingdomServerBundle\Service\ActivityManager;
use Kori\KingdomServerBundle\Service\EffectManager;
use Kori\KingdomServerBundle\Service\RuleManager;
use Kori\KingdomServerBundle\Service\ServerManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CronCommand
 * @package Kori\KingdomServerBundle\Tests\Units\Command
 */
class CronCommand extends test
{

    public function testCommand()
    {
        $entityManager = new \mock\Doctrine\ORM\EntityManager();

        $ruleManager = new RuleManager();
        $ruleManager->addBuildRule(new \Kori\KingdomServerBundle\Rules\Build\Basic());
        $ruleManager->addAttackRule(new \Kori\KingdomServerBundle\Rules\Attack\Basic());
        $ruleManager->addInfluenceRule(new \Kori\KingdomServerBundle\Rules\Influence\Standard());

        $serverManager = new ServerManager(["my_server" => [
            "rate" => 1,
            "db_connection" => $entityManager,
            "days_of_protection" => 7,
            "rules" => ["build" => ["basic"], "attack" => "basic", "influence" => "standard"]
        ]], [], $ruleManager, new EffectManager());

        $activityManager = new ActivityManager($serverManager);
        $activityManager->addActivity(new ProcessBattle());

        $container = new \mock\Symfony\Component\DependencyInjection\ContainerInterface();

        $command = new TestedCommand();
        $command->setContainer($container);
        $application = new Application();
        $application->add($command);

        $command = $application->find('kingdom:cron');

        $commandTester = new CommandTester($command);


    }

}
