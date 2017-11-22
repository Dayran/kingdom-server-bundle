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

namespace Kori\KingdomServerBundle\Tests\Units\Repository;


use atoum\test;
use Kori\KingdomServerBundle\Entity\Account;
use Kori\KingdomServerBundle\Entity\Quest;


/**
 * Class QuestRepository
 * @package Kori\KingdomServerBundle\Tests\Units\Repository
 */
class QuestRepository extends test
{

    public function testQuest()
    {
        $account = new Account();

        $quests = [];

        $this
            ->given($this->mockGenerator()->orphanize('__construct'))
            ->given($objectManager = new \mock\Doctrine\Common\Persistence\ObjectManager())
            ->given($this->mockGenerator()->orphanize('__construct'))
            ->given($meta = new \mock\Doctrine\ORM\Mapping\ClassMetadata())
            ->given($manager = new \mock\Kori\KingdomServerBundle\Repository\QuestRepository($objectManager, $meta))
            ->given($buildingLevel = new \mock\Kori\KingdomServerBundle\Entity\BuildingLevel())
            ->and($this->calling($buildingLevel)->getId = function() {
                return 1;
            })
            ->when($quest = $manager->createQuest("Starter Quest"))
            ->then(
                $this->object($quest)->isInstanceOf(Quest::class, "Failed to return proper object")
                    ->and($this->string($quest->getName())->isEqualTo("Starter Quest", "Name of quest failed to set properly"))
                    ->and($quests[] = $quest)
            )
            ->when($manager->addStep($quest, $buildingLevel))
            ->then(
                $this->integer($quest->getSteps()->count())->isEqualTo(1, "There should be 1 quest step")
            )
            ->given($this->calling($manager)->getAvailableQuest = function() use ($quests) {
                return $quests;
            })
            ->when($manager->process($account))
            ->then(

            )

        ;
    }
}
