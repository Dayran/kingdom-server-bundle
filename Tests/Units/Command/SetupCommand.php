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
use Kori\KingdomServerBundle\Command\SetupCommand as TestedCommand;
use Kori\KingdomServerBundle\Entity\ServerStats;
use Kori\KingdomServerBundle\Service\EffectManager;
use Kori\KingdomServerBundle\Service\GeneratorManager;
use Kori\KingdomServerBundle\Service\RuleManager;
use Kori\KingdomServerBundle\Service\ServerManager;
use Kori\KingdomServerBundle\Tests\Generators\BuildingGenerator;
use Kori\KingdomServerBundle\Tests\Generators\CustomWorldGenerator;
use Kori\KingdomServerBundle\Tests\Generators\RaceGenerator;
use Kori\KingdomServerBundle\Tests\Generators\SampleWorldGenerator;
use Kori\KingdomServerBundle\Tests\Generators\TechnologyGenerator;
use Kori\KingdomServerBundle\Tests\Generators\UnitGenerator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SetupCommand extends test
{
    public function testCommand()
    {
        $generatorManager = new GeneratorManager();
        $generatorManager->addGenerator(new CustomWorldGenerator());
        $generatorManager->addGenerator(new SampleWorldGenerator());
        $generatorManager->addGenerator(new BuildingGenerator());
        $generatorManager->addGenerator(new RaceGenerator());
        $generatorManager->addGenerator(new TechnologyGenerator());
        $generatorManager->addGenerator(new UnitGenerator());

        $ruleManager = new RuleManager();
        $ruleManager->addBuildRule(new \Kori\KingdomServerBundle\Rules\Build\Basic());
        $ruleManager->addAttackRule(new \Kori\KingdomServerBundle\Rules\Attack\Basic());

        $entityManager = new \mock\Doctrine\ORM\EntityManager();
        $serverManager = new ServerManager(["my_server" => [
            "rate" => 1,
            "db_connection" => $entityManager,
            "days_of_protection" => 7,
            "rules" => ["build" => ["basic"], "attack" => "basic"]
        ]], [], $ruleManager, new EffectManager());

        $container = new \mock\Symfony\Component\DependencyInjection\ContainerInterface();
        $this->calling($container)->get = function ($v) use($generatorManager, $serverManager)  {
            if($v === 'kori_kingdom.generator_manager')
                return $generatorManager;
            return $serverManager;
        };

        $this->mockGenerator()->orphanize('__construct');
        $this->mockGenerator()->shuntParentClassCalls();
        $repository =  new \mock\Doctrine\ORM\EntityRepository;

        $this->calling($repository)->findOneBy = function() {
            return null;
        };

        $this->calling($entityManager)->getRepository = function() use($repository) {
            return $repository;
        };

        $command = new TestedCommand();
        $command->setContainer($container);
        $application = new Application();
        $application->add($command);

        $command = $application->find('kingdom:setup');

        $commandTester = new CommandTester($command);
        $error = $commandTester->execute(array('command' => $command->getName(),
            "name" => "my_server",
            '--generator' => ["SampleWorld", "race", "building", "technology", "unit"]
        ));

        $this->integer($error)->isEqualTo(0, "There should be no error");

        $error = $commandTester->execute(array('command' => $command->getName(),
            "name" => "my_server",
            '-ignore' => ''
        ));
        $this->integer($error)->isEqualTo(0, "There should be no error because restriction is ignored");

        try {
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["CustomWorld", "SampleWorld", "race", "building", "technology", "unit"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Multiple generators of the same type detected.");
        }

        try {
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["CustomWorld", "race", "building", "technology", "unit"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage(sprintf("%s has failed on generator pass.", CustomWorldGenerator::class));
        }

        try {
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server"
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("World generator not found.");
        }

        try {
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["SampleWorld"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Race generator not found.");
        }

        try {
            //generator name is insensitive
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["sampleworld", "race"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Buildings generator not found.");
        }

        try {
            //generator name is insensitive
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["sampleworld", "race", "building"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Technology generator not found.");
        }

        try {
            //generator name is insensitive
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["sampleworld", "race", "building", "technology"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Units generator not found.");
        }

        $this->calling($repository)->findOneBy = function() {
            return new ServerStats();
        };

        try {
            //generator name is insensitive
            $commandTester->execute(array('command' => $command->getName(),
                "name" => "my_server",
                '--generator' => ["SampleWorld", "race", "building", "technology", "unit"]
            ));
        } catch (\Exception $exception) {
            $this->exception($exception)->hasMessage("Server is already populated.");
        }

    }
}
