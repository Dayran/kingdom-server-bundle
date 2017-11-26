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


use Doctrine\Common\Collections\Collection;
use Kori\KingdomServerBundle\Traits\BuildingRequirements;
use Kori\KingdomServerBundle\Traits\Population;
use Kori\KingdomServerBundle\Traits\ResourceRequirements;
use Kori\KingdomServerBundle\Traits\TechnologyRequirements;
use Kori\KingdomServerBundle\Traits\TimeCost;

/**
 * Class BuildingLevel
 * @package Kori\KingdomServerBundle\Entity
 */
class BuildingLevel
{
    use TimeCost;
    use Population;
    use BuildingRequirements;
    use ResourceRequirements;
    use TechnologyRequirements;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var BuildingType
     */
    protected $buildingType;

    /**
     * @var int
     */
    protected $culture = 0;

    /**
     * @var Collection
     */
    protected $awardedTechnologies;

    /**
     * @var int
     */
    protected $positionLimit = 0;

    /**
     * @var int
     */
    protected $generateClay = 0;

    /**
     * @var int
     */
    protected $generateWood = 0;

    /**
     * @var int
     */
    protected $generateWheat = 0;

    /**
     * @var int
     */
    protected $generateIron = 0;

    /**
     * @var int
     */
    protected $storeClay = 0;

    /**
     * @var int
     */
    protected $storeIron = 0;

    /**
     * @var int
     */
    protected $storeWheat = 0;

    /**
     * @var int
     */
    protected $storeWood = 0;

    /**
     * @var int
     */
    protected $merchants = 0;

    /**
     * @var int
     */
    protected $level;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return BuildingType
     */
    public function getBuildingType(): BuildingType
    {
        return $this->buildingType;
    }

    /**
     * @param BuildingType $buildingType
     */
    public function setBuildingType(BuildingType $buildingType)
    {
        $this->buildingType = $buildingType;
    }

    /**
     * @return int
     */
    public function getCulture(): int
    {
        return $this->culture;
    }

    /**
     * @param int $culture
     */
    public function setCulture(int $culture)
    {
        $this->culture = $culture;
    }

    /**
     * @return Collection
     */
    public function getAwardedTechnologies(): Collection
    {
        return $this->awardedTechnologies;
    }

    /**
     * @param Collection $awardedTechnologies
     */
    public function setAwardedTechnologies(Collection $awardedTechnologies)
    {
        $this->awardedTechnologies = $awardedTechnologies;
    }

    /**
     * @return int
     */
    public function getPositionLimit(): int
    {
        return $this->positionLimit;
    }

    /**
     * @param int $positionLimit
     */
    public function setPositionLimit(int $positionLimit)
    {
        $this->positionLimit = $positionLimit;
    }

    /**
     * @return int
     */
    public function getGenerateClay(): int
    {
        return $this->generateClay;
    }

    /**
     * @param int $generateClay
     */
    public function setGenerateClay(int $generateClay)
    {
        $this->generateClay = $generateClay;
    }

    /**
     * @return int
     */
    public function getGenerateWood(): int
    {
        return $this->generateWood;
    }

    /**
     * @param int $generateWood
     */
    public function setGenerateWood(int $generateWood)
    {
        $this->generateWood = $generateWood;
    }

    /**
     * @return int
     */
    public function getGenerateWheat(): int
    {
        return $this->generateWheat;
    }

    /**
     * @param int $generateWheat
     */
    public function setGenerateWheat(int $generateWheat)
    {
        $this->generateWheat = $generateWheat;
    }

    /**
     * @return int
     */
    public function getGenerateIron(): int
    {
        return $this->generateIron;
    }

    /**
     * @param int $generateIron
     */
    public function setGenerateIron(int $generateIron)
    {
        $this->generateIron = $generateIron;
    }

    /**
     * @return int
     */
    public function getMerchants(): int
    {
        return $this->merchants;
    }

    /**
     * @param int $merchants
     */
    public function setMerchants(int $merchants)
    {
        $this->merchants = $merchants;
    }

    /**
     * @return int
     */
    public function getStoreClay(): int
    {
        return $this->storeClay;
    }

    /**
     * @param int $storeClay
     */
    public function setStoreClay(int $storeClay)
    {
        $this->storeClay = $storeClay;
    }

    /**
     * @return int
     */
    public function getStoreIron(): int
    {
        return $this->storeIron;
    }

    /**
     * @param int $storeIron
     */
    public function setStoreIron(int $storeIron)
    {
        $this->storeIron = $storeIron;
    }

    /**
     * @return int
     */
    public function getStoreWheat(): int
    {
        return $this->storeWheat;
    }

    /**
     * @param int $storeWheat
     */
    public function setStoreWheat(int $storeWheat)
    {
        $this->storeWheat = $storeWheat;
    }

    /**
     * @return int
     */
    public function getStoreWood(): int
    {
        return $this->storeWood;
    }

    /**
     * @param int $storeWood
     */
    public function setStoreWood(int $storeWood)
    {
        $this->storeWood = $storeWood;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level)
    {
        $this->level = $level;
    }

}
