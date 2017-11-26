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


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kori\KingdomServerBundle\Traits\Resources;

/**
 * Class Town
 * @package Kori\KingdomServerBundle\Entity
 */
class Town
{

    use Resources;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $buildings;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var Field
     */
    protected $field;

    /**
     * @var int
     */
    protected $lastTick;

    /**
     * @var Avatar
     */
    protected $avatar;

    /**
     * @var Collection
     */
    protected $units;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getBuildings(): Collection
    {
        return $this->buildings?: $this->buildings = new ArrayCollection();
    }

    /**
     * @param Collection $buildings
     */
    public function setBuildings(Collection $buildings)
    {
        $this->buildings = $buildings;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field)
    {
        $this->field = $field;
    }

    /**
     * @return int
     */
    public function getLastTick(): int
    {
        return $this->lastTick = time();
    }

    /**
     * @param int $lastTick
     */
    public function setLastTick(int $lastTick)
    {
        $this->lastTick = $lastTick;
    }

    /**
     * @return array
     */
    public function getGenerateRate(): array
    {
        $rates = [
            "wood" => 0,
            "clay" => 0,
            "iron" => 0,
            "wheat" => 0
        ];
        foreach($this->getBuildings() as $building)
        {
            if($building instanceof TownLog)
            {
                $rates["wood"] += $building->getBuildingLevel()->getGenerateWood();
                $rates["clay"] += $building->getBuildingLevel()->getGenerateClay();
                $rates["iron"] += $building->getBuildingLevel()->getGenerateIron();
                $rates["wheat"] += $building->getBuildingLevel()->getGenerateWheat();
            }
        }
        return $rates;
    }

    /**
     * @return array
     */
    public function getStorage(): array
    {
        $storage = [
            "wood" => 0,
            "clay" => 0,
            "iron" => 0,
            "wheat" => 0
        ];
        foreach($this->getBuildings() as $building)
        {
            if($building instanceof TownLog)
            {
                $storage["wood"] += $building->getBuildingLevel()->getStoreWood();
                $storage["clay"] += $building->getBuildingLevel()->getStoreClay();
                $storage["iron"] += $building->getBuildingLevel()->getStoreIron();
                $storage["wheat"] += $building->getBuildingLevel()->getStoreWheat();
            }
        }
        return $storage;
    }

    /**
     * @param int $position
     * @return bool
     */
    public function buildingPositionIsEmpty(int $position): bool
    {
        $log = $this->getBuildings()->filter(function (TownLog $l) use($position) {
            return $l->getPosition() == $position;
        });
        return $log->isEmpty();
    }

    /**
     * @return Collection
     */
    public function getConstructingBuilding(): Collection
    {
        return $this->getBuildings()->filter(function (TownLog $log) {
            return $log->getTtc() > time();
        });
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     */
    public function setAvatar(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return Collection
     */
    public function getUnits(): Collection
    {
        return $this->units?: $this->units = new ArrayCollection();
    }

    /**
     * @param Collection $units
     */
    public function setUnits(Collection $units)
    {
        $this->units = $units;
    }

    /**
     * @param Unit $unit
     * @return TownUnits|null
     */
    public function getUnit(Unit $unit): ?TownUnits
    {
        $tu = $this->getUnits()->filter(function (TownUnits $townUnits) use($unit) {
            return $townUnits->getUnit() === $unit;
        });

        return $tu->first();
    }

}
