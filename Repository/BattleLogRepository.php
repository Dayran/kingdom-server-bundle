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
use Kori\KingdomServerBundle\Entity\BattleLog;
use Kori\KingdomServerBundle\Entity\Town;

/**
 * Class BattleLogRepository
 * @package Kori\KingdomServerBundle\Repository
 */
class BattleLogRepository extends EntityRepository
{

    public function getBattlesToProcess(): array
    {
        $qb = $this->createQueryBuilder('b');
        $qb->where('b.timeTaken <= ?1');
        $qb->andWhere('b.processed is false');
        $qb->setParameter(1, time());
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Town $attacker
     * @param Town $defender
     * @param int $type
     * @param array $units
     * @param bool $sendAvatar
     * @throws \RuntimeException
     * @return BattleLog
     */
    public function createBattle(Town $attacker, Town $defender, int $type, array $units, bool $sendAvatar = false): BattleLog
    {
        $log = new BattleLog();
        $log->setAttackTown($attacker);
        $log->setDefendTown($defender);
        $log->setType($type);

        $sendUnits = [];

        $attackCalvary = 0;
        $attackInfantry = 0;
        $lootCapacity = 0;

        foreach ($units as $unit)
        {
            $tu = $attacker->getUnit($unit->getType());
            $tu->setCount($tu->getCount() - $unit->getCount());
            if($tu->getCount() < 0)
                throw new \RuntimeException("There are not enough units in town");
            $type = $tu->getUnit();
            //@todo calculate strength with modifiers
            if($type->isCavalry())
                $attackCalvary += $type->getAttack() * $unit->getCount();
            else
                $attackInfantry += $type->getAttack() * $unit->getCount();
            $lootCapacity += $type->getCarry() * $unit->getCount();
        }

        $log->setAttackCalvaryStrength($attackCalvary);
        $log->setAttackInfantryStrength($attackInfantry);
        $log->setAttackUnits($sendUnits);
        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->persist($attacker);

        if($sendAvatar)
        {
            $attacker->getAccount()->getAvatar()->setBattleLog($log);
            $this->getEntityManager()->persist($attacker->getAccount()->getAvatar());
        }

        $this->getEntityManager()->flush();
        return $log;
    }

    /**
     * @param Town $town
     * @param bool $unprocessedOnly
     * @return array
     */
    public function getBattles(Town $town, bool $unprocessedOnly = true): array
    {
        $qb = $this->createQueryBuilder('b');
        $qb->where($qb->expr()->orX(
            $qb->expr()->eq('b.attackTown', '?1'),
            $qb->expr()->eq('b.defendTown', '?1')
        ));
        $qb->setParameter(1, $town);
        if($unprocessedOnly)
        {
            $qb->andWhere('b.timeTaken <= ?2');
            $qb->andWhere('b.processed is false');
            $qb->setParameter(2, time());
        }

        return $qb->getQuery()->getArrayResult();
    }
}
