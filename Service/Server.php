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
use Kori\KingdomServerBundle\Entity\Town;
use Kori\KingdomServerBundle\Repository\AccountRepository;
use Kori\KingdomServerBundle\Repository\FieldRepository;
use Kori\KingdomServerBundle\Repository\MessageRepository;
use Kori\KingdomServerBundle\Repository\QuestRepository;
use Kori\KingdomServerBundle\Rules\AttackRuleInterface;
use Kori\KingdomServerBundle\Rules\BuildRuleInterface;
use Kori\KingdomServerBundle\Rules\InfluenceRuleInterface;

/**
 * Class Server
 * @package Kori\KingdomServerBundle\Service
 */
final class Server
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
     * @var $buildRules
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
     * Server constructor.
     * @param EntityManager $entityManager
     * @param int $rate
     * @param int $protectionDays
     */
    public function __construct(EntityManager $entityManager, int $rate, int $protectionDays)
    {
        $this->em = $entityManager;
        $this->rate = $rate;
        $this->protectionDays = $protectionDays;
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
     * @param array $buildRules
     * @return void
     */
    public function setBuildRules(array $buildRules): void
    {
        $this->buildRules = $buildRules;
    }

    /**
     * @param AttackRuleInterface $attackRule
     */
    public function setAttackRule(AttackRuleInterface $attackRule)
    {
        $this->attackRule = $attackRule;
    }

    /**
     * @return InfluenceRuleInterface
     */
    public function getInfluenceRule(): InfluenceRuleInterface
    {
        return $this->influenceRule;
    }

    /**
     * @param InfluenceRuleInterface $influenceRule
     */
    public function setInfluenceRule(InfluenceRuleInterface $influenceRule)
    {
        $this->influenceRule = $influenceRule;
    }

    /**
     * Checks and build building
     *
     * @param Town $town
     * @param BuildingLevel $buildingLevel
     * @param $position
     * @return bool
     */
    public function build(Town $town, BuildingLevel $buildingLevel, int $position): bool
    {
        foreach($this->buildRules as $buildRule)
        {
            if($buildRule instanceof BuildRuleInterface && !$buildRule->comply($town, $buildingLevel, $position))
                return false;
        }
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

        return true;
    }

    /**
     * @param Town $attacker
     * @param Town $defender
     * @param int $type
     * @param array $units
     * @param bool $sendAvatar
     */
    public function battle(Town $attacker, Town $defender, int $type, array $units, bool $sendAvatar = false)
    {
        $log = new BattleLog();
        $log->setAttackTown($attacker);
        $log->setDefendTown($defender);
        $log->setType($type);

        foreach ($units as $unit)
        {
            //@todo calculate strength
        }

        //@todo remove units from attacker
        //@todo if sending avatar set avatar to be away
        //$attacker->getAccount()->getAvatar()->setBattleLog($log);
        //$this->em->persist($log);
        //$this->em->flush();
    }

    /**
     * Process unprocessed battles server wide
     */
    public function processBattles()
    {
        $query = $this->getEntityManager()->createQuery(sprintf("SELECT b from %s b where b.proccessed is FALSE and b.eta <= %d", BattleLog::class, time()));
        foreach($query->getResult() as $result)
        {
            $this->attackRule->finalize($result);
        }
    }

    /**
     * @param Town $town
     */
    public function tick(Town $town)
    {
        $rates = $town->getGenerateRate();
        $diff = time() - $town->getLastTick();
        //@todo get modifiers
        $town->setLastTick(time());
        //$this->em->persist($town);
        //$this->em->flush();

        //@todo handle battles

        $this->getQuestManager()->process($town->getAccount());
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
