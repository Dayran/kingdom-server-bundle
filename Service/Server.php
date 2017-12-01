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

namespace Kori\KingdomServerBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Kori\KingdomServerBundle\Activity\ActivityInterface;
use Kori\KingdomServerBundle\Entity\Account;
use Kori\KingdomServerBundle\Entity\Avatar;
use Kori\KingdomServerBundle\Entity\Bans;
use Kori\KingdomServerBundle\Entity\BattleLog;
use Kori\KingdomServerBundle\Entity\BuildingLevel;
use Kori\KingdomServerBundle\Entity\Consumable;
use Kori\KingdomServerBundle\Entity\ConsumablesEffect;
use Kori\KingdomServerBundle\Entity\Field;
use Kori\KingdomServerBundle\Entity\Message;
use Kori\KingdomServerBundle\Entity\Quest;
use Kori\KingdomServerBundle\Entity\Race;
use Kori\KingdomServerBundle\Entity\ServerStats;
use Kori\KingdomServerBundle\Entity\TechnologyLevel;
use Kori\KingdomServerBundle\Entity\Town;
use Kori\KingdomServerBundle\Entity\TownLog;
use Kori\KingdomServerBundle\Entity\Unit;
use Kori\KingdomServerBundle\Entity\UnitQueue;
use Kori\KingdomServerBundle\Events;
use Kori\KingdomServerBundle\Repository\AccountRepository;
use Kori\KingdomServerBundle\Repository\BattleLogRepository;
use Kori\KingdomServerBundle\Repository\FieldRepository;
use Kori\KingdomServerBundle\Repository\MessageRepository;
use Kori\KingdomServerBundle\Repository\QuestRepository;
use Kori\KingdomServerBundle\Repository\UnitRepository;
use Kori\KingdomServerBundle\Rules\AttackRuleInterface;
use Kori\KingdomServerBundle\Rules\BuildRuleInterface;
use Kori\KingdomServerBundle\Rules\InfluenceRuleInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class Server
 * @package Kori\KingdomServerBundle\Service
 */
class Server
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $rate;

    /**
     * @var int
     */
    protected $protectionDays;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var array
     */
    protected $buildRules = [];

    /**
     * @var AttackRuleInterface
     */
    protected $attackRule;

    /**
     * @var InfluenceRuleInterface
     */
    protected $influenceRule;

    /**
     * @var EffectManager
     */
    protected $effectManager;

    /**
     * @var RuleManager
     */
    protected $ruleManager;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * Server constructor.
     * @param EntityManagerInterface $entityManager
     * @param int $rate
     * @param int $protectionDays
     * @param array $rules
     */
    public function __construct(EntityManagerInterface $entityManager, int $rate, int $protectionDays, array $rules)
    {
        $this->em = $entityManager;
        $this->rate = $rate;
        $this->protectionDays = $protectionDays;
        $this->rules = $rules;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->em;
    }

    /**
     * @return int
     */
    public function getRate(): int
    {
        return $this->rate;
    }

    /**
     * @param Account $account
     * @param string $ip
     * @return bool
     */
    public function isBanned(Account $account, string $ip): bool
    {
        $qb = $this->getEntityManager()->createQuery(
            sprintf("SELECT count(b) from %s b where (b.TYPE = '%s' and b.value = '%s') or (b.TYPE = '%s' and b.VALUE = '%s')",
            Bans::class,
            Bans::ACCOUNT, $account->getId(),
                Bans::IP, $ip
            )
        );
        return $qb->getSingleScalarResult() > 0;
    }

    /**
     * @param Account $account
     * @param string $ip
     */
    public function ban(Account $account, string $ip = '')
    {
        $ban = new Bans();
        $ban->setType(Bans::ACCOUNT);
        $ban->setValue($account->getId());
        $this->getEntityManager()->persist($ban);

        if($ip !== '') {
            $ban = new Bans();
            $ban->setType(Bans::IP);
            $ban->setValue($ip);
            $this->getEntityManager()->persist($ban);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @return InfluenceRuleInterface
     */
    public function getInfluenceRule(): InfluenceRuleInterface
    {
        return $this->influenceRule;
    }

    /**
     * Checks and build building
     *
     * @param Town $town
     * @param BuildingLevel $buildingLevel
     * @param $position
     * @throws \RuntimeException
     * @return bool
     */
    public function build(Town $town, BuildingLevel $buildingLevel, int $position): bool
    {
        if(empty($this->buildRules))
            throw new \RuntimeException("Build rules are empty");

        foreach($this->buildRules as $buildRule)
        {
            if($buildRule instanceof BuildRuleInterface && !$buildRule->comply($town, $buildingLevel, $position))
                return false;
        }

        $log = new TownLog();
        $log->setBuildingLevel($buildingLevel);
        $log->setTown($town);
        $log->setPosition($position);
        $log->setTtc(time() + $buildingLevel->getTimeTaken());

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();

        if($this->dispatcher != null)
            $this->dispatcher->dispatch(Events::POST_BUID, new GenericEvent($log));
        return true;
    }

    /**
     * @return AccountRepository
     */
    public function getAccountManager(): AccountRepository
    {
        $repository = $this->getEntityManager()->getRepository(Account::class);
        $repository->setProtectionPeriod($this->protectionDays);
        return $repository;
    }

    /**
     * @return FieldRepository
     */
    public function getFieldManager(): FieldRepository
    {
        return $this->getEntityManager()->getRepository(Field::class);
    }

    /**
     * @return MessageRepository
     */
    public function getMessageManager(): MessageRepository
    {
        return $this->getEntityManager()->getRepository(Message::class);
    }

    /**
     * @return QuestRepository
     */
    public function getQuestManager(): QuestRepository
    {
        return $this->getEntityManager()->getRepository(Quest::class);
    }

    /**
     * @return BattleLogRepository
     */
    public function getBattleManager(): BattleLogRepository
    {
        return $this->getEntityManager()->getRepository(BattleLog::class);
    }

    /**
     * @return UnitRepository
     */
    public function getUnitManager(): UnitRepository
    {
        return $this->getEntityManager()->getRepository(Unit::class);
    }

    /**
     * @param string $name
     * @return ServerStats
     */
    public function getStatus(string $name): ?ServerStats
    {
        return $this->getEntityManager()->getRepository(ServerStats::class)->findOneBy(['name' => $name]);
    }

    /**
     * @return array
     */
    public function getRaces(): array
    {
        return $this->getEntityManager()->getRepository(Race::class)->findAll();
    }

    /**
     * @param EffectManager $effectManager
     * @return void
     */
    public function setEffectManager(EffectManager $effectManager): void
    {
        $this->effectManager = $effectManager;
    }

    /**
     * @param EventDispatcher $dispatcher
     */
    public function setDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param RuleManager $ruleManager
     */
    public function setRuleManager(RuleManager $ruleManager)
    {
        $this->ruleManager = $ruleManager;
        if(array_key_exists("build", $this->rules))
            $this->buildRules = $ruleManager->getBuildRules($this->rules['build']);
        if(array_key_exists("attack", $this->rules))
            $this->attackRule = $ruleManager->getAttackRule($this->rules['attack']);
        if(array_key_exists("influence", $this->rules))
            $this->influenceRule = $ruleManager->getInfluenceRule($this->rules['influence']);
    }

    /**
     * @param Avatar $avatar
     * @param Consumable $consumable
     * @param bool $ignoreAway
     * @return bool
     */
    public function consume(Avatar $avatar, Consumable $consumable, bool $ignoreAway = false): bool
    {
        //@todo add check if avatar is carrying/have object
        if($avatar->isAway() && !$ignoreAway)
            return false;
        $consumable->getEffects()->filter(function (ConsumablesEffect $effect) use($avatar) {
            $this->effectManager->process($avatar, $effect->getType(), $effect->getValue());
        });
        $this->getEntityManager()->persist($avatar);
        $this->getEntityManager()->flush();

        if($this->dispatcher != null)
            $this->dispatcher->dispatch(Events::POST_CONSUME, new GenericEvent($avatar, ['item' => $consumable]));


        return true;
    }

    /**
     * @param Town $town
     * @param Unit $unit
     * @param int $count
     * @throws \RuntimeException
     * @return bool
     */
    public function train(Town $town, Unit $unit, int $count): bool
    {
        $units = $town->getAccount()->getRace()->getUnits();
        if(!$units->contains($unit))
            throw new \RuntimeException("Attempting to train unit that is not available to the account's race");

        //@todo check if have resource
        //@todo check building requirement
        //@todo training rule
        $trainQueue = new UnitQueue();
        
        if($this->dispatcher != null)
            $this->dispatcher->dispatch(Events::POST_TRAINING, new GenericEvent($trainQueue));

        return true;
    }

    /**
     * Process unprocessed battles server wide and return number of battles processed
     * @param array $toProcess Array of battles to process
     * @param bool $delayInfluence Delays the processing of influence after battle
     * @return int
     */
    public function processBattles(array $toProcess = [], bool $delayInfluence = false): int
    {
        $battles = $toProcess ?? $this->getBattleManager()->getBattlesToProcess();

        for($i = 0; $i < count($battles); $i++)
        {
            $this->attackRule->finalize($battles[$i]);
            $battles[$i]->setProcessed(true);
            $this->getEntityManager()->persist($battles[$i]);

            if(!$delayInfluence)
            {
               $this->calculateInfluence($battles[$i]->getAttackTown());
               $this->calculateInfluence($battles[$i]->getDefendTown());
            }

            //Batch flushing after 20
            if($i % 20 === 0)
                $this->getEntityManager()->flush();
        }

        $this->getEntityManager()->flush();

        return count($battles);
    }

    /**
     * @param Town $town
     */
    public function calculateInfluence(Town $town)
    {

    }

    /**
     * @param TechnologyLevel $level
     * @param Town $town
     * @return bool
     */
    public function research(TechnologyLevel $level, Town $town): bool
    {
        return false;
    }

    /**
     * Primary update function to process individual town, including resource, battle. Will update influence.
     * @param Town $town
     * @param bool $updateQuest
     * @param bool $updateBattle
     */
    public function tick(Town $town, bool $updateQuest = true, bool $updateBattle = true)
    {
        $rates = $town->getGenerateRate();
        $diff = time() - $town->getLastTick();
        //@todo get modifiers
        $storage = $town->getStorage();
        $wood = $rates["wood"] * $diff * $this->rate;
        $iron = $rates["iron"] * $diff * $this->rate;
        $clay = $rates["clay"] * $diff * $this->rate;
        $wheat = $rates["wheat"] * $diff * $this->rate;

        $town->setWood(min($town->getWood() + $wood, $storage["wood"]));
        $town->setIron(min($town->getIron() + $iron, $storage["iron"]));
        $town->setClay(min($town->getClay() + $clay, $storage["clay"]));
        $town->setWheat(min($town->getWheat() + $wheat, $storage["wheat"]));
        $town->setLastTick(time());

        $this->em->persist($town);
        $this->em->flush();

        if($updateBattle)
            $this->processBattles($this->getBattleManager()->getBattles($town));

        if($updateQuest)
            $this->getQuestManager()->process($town->getAccount());

        if($this->dispatcher != null)
            $this->dispatcher->dispatch(Events::POST_TICK, new GenericEvent($town));
    }

    /**
     * @param ActivityInterface $activity
     * @return bool
     */
    public function canTrigger(ActivityInterface $activity) : bool
    {
        $upTime = time() - $this->getStatus(ServerStats::CREATED_AT)->getValue();
        if($activity->isRepeated())
        {
            return $upTime % $activity->getSchedule() === 0;
        } else {
            return $activity->getSchedule() >= $upTime;
        }
    }

}
