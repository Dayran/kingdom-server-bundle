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

namespace Kori\KingdomServerBundle\Entity;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Kori\KingdomServerBundle\Traits\Resources;
use Kori\KingdomServerBundle\Traits\TimeCost;

/**
 * Class BattleLog
 * @package Kori\KingdomServerBundle\Entity
 */
class BattleLog
{

    use Resources;
    use TimeCost;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Town
     */
    protected $attackTown;

    /**
     * @var Town
     */
    protected $defendTown;

    /**
     * @var array
     */
    protected $attackUnits = [];

    /**
     * @var int
     */
    protected $attackCalvaryStrength;

    /**
     * @var int
     */
    protected $attackInfantryStrength;

    /**
     * @var int
     */
    protected $lootCapacity;

    /**
     * @var int
     */
    protected $eta;

    /**
     * @var bool
     */
    protected $processed = false;

    /**
     * @var int
     */
    protected $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Town
     */
    public function getAttackTown(): Town
    {
        return $this->attackTown;
    }

    /**
     * @param Town $attackTown
     */
    public function setAttackTown(Town $attackTown)
    {
        $this->attackTown = $attackTown;
    }

    /**
     * @return Town
     */
    public function getDefendTown(): Town
    {
        return $this->defendTown;
    }

    /**
     * @param Town $defendTown
     */
    public function setDefendTown(Town $defendTown)
    {
        $this->defendTown = $defendTown;
    }

    /**
     * @return array
     */
    public function getAttackUnits(): array
    {
        return $this->attackUnits;
    }

    /**
     * @param array $attackUnits
     */
    public function setAttackUnits(array $attackUnits)
    {
        $this->attackUnits = $attackUnits;
    }

    /**
     * @return int
     */
    public function getAttackCalvaryStrength(): int
    {
        return $this->attackCalvaryStrength;
    }

    /**
     * @param int $attackCalvaryStrength
     */
    public function setAttackCalvaryStrength(int $attackCalvaryStrength)
    {
        $this->attackCalvaryStrength = $attackCalvaryStrength;
    }

    /**
     * @return int
     */
    public function getAttackInfantryStrength(): int
    {
        return $this->attackInfantryStrength;
    }

    /**
     * @param int $attackInfantryStrength
     */
    public function setAttackInfantryStrength(int $attackInfantryStrength)
    {
        $this->attackInfantryStrength = $attackInfantryStrength;
    }

    /**
     * @return int
     */
    public function getLootCapacity(): int
    {
        return $this->lootCapacity;
    }

    /**
     * @param int $lootCapacity
     */
    public function setLootCapacity(int $lootCapacity)
    {
        $this->lootCapacity = $lootCapacity;
    }

    /**
     * @return int
     */
    public function getEta(): int
    {
        return $this->eta;
    }

    /**
     * @param int $eta
     */
    public function setEta(int $eta)
    {
        $this->eta = $eta;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * @param bool $processed
     */
    public function setProcessed(bool $processed)
    {
        $this->processed = $processed;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoadHandler(LifecycleEventArgs $event)
    {
        $actual = [];
        foreach($this->getAttackUnits() as $unit)
        {
            $obj = new BattleLogUnit();
            $obj->setCount($unit['count']);
            $type = $event->getEntityManager()->getRepository(Unit::class)->find($unit['type']);
            $obj->setType($type);
            $actual[] = $obj;
        }
        $this->setAttackUnits($actual);
    }

}
