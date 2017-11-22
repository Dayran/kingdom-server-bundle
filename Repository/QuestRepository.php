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

namespace Kori\KingdomServerBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Kori\KingdomServerBundle\Entity\Account;
use Kori\KingdomServerBundle\Entity\BuildingLevel;
use Kori\KingdomServerBundle\Entity\Quest;
use Kori\KingdomServerBundle\Entity\QuestLog;
use Kori\KingdomServerBundle\Entity\QuestStep;
use Kori\KingdomServerBundle\Entity\Unit;

/**
 * Class QuestRepository
 * @package Kori\KingdomServerBundle\Repository
 */
class QuestRepository extends EntityRepository
{

    /**
     * @param string $name
     * @param array $precursors
     * @return Quest
     */
    public function createQuest(string $name, array $precursors = []): Quest
    {
        $quest = new Quest();
        $quest->setName($name);

        foreach ($precursors as $precursor)
        {
            if($precursor instanceof Quest)
                $quest->getPrecursors()->add($quest);
            else
                $quest->getPrecursors()->add($this->find($precursor));
        }

        $this->getEntityManager()->persist($quest);
        $this->getEntityManager()->flush();

        return $quest;
    }

    /**
     * @param Quest $quest
     * @param $requirement
     * @param int $value
     * @param array $award
     */
    public function addStep(Quest $quest, $requirement, array $award = [], int $value = null)
    {
        $step = new QuestStep();
        if($requirement instanceof BuildingLevel)
        {
            $step->setType(QuestStep::BUILDING_REQ);
            $step->setRequirement($requirement->getId());
        }
        if($requirement instanceof Unit)
        {
            $step->setType(QuestStep::TROOP_REQ);
            $step->setRequirement($requirement->getId());
            $step->setValue($value);
        }

        $step->setQuest($quest);

        $this->getEntityManager()->persist($quest);
        $this->getEntityManager()->flush();

        $quest->getSteps()->add($step);
    }

    /**
     * @param Account $account
     * @return array
     */
    public function getAvailableQuest(Account $account): array
    {
        return [];
    }

    /**
     * @param Account $account
     */
    public function process(Account $account)
    {
        $quests = $this->getAvailableQuest($account);

        array_walk($quests, function(Quest $quest) use ($account) {
            $steps = $quest->getSteps()->toArray();
            array_walk($steps, function(QuestStep $questStep) use($account) {
                if($questStep->getType() == QuestStep::BUILDING_REQ) {
                    //@todo fix check if building is completed
                    //$this->completeStep($questStep, $account);
                    return;
                }
            });
        });
    }

    /**
     * @param QuestStep $questStep
     * @param Account $account
     */
    private function completeStep(QuestStep $questStep, Account $account)
    {
        $log = new QuestLog();
        $log->setStep($questStep);
        $log->setAccount($account);
        $log->getCreatedAt();

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
    }


}
