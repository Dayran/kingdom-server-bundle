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
use Kori\KingdomServerBundle\Traits\BuildingRequirements;
use Kori\KingdomServerBundle\Traits\ResourceRequirements;
use Kori\KingdomServerBundle\Traits\TimeCost;

/**
 * Class TechnologyLevel
 * @package Kori\KingdomServerBundle\Entity
 */
class TechnologyLevel
{

    use TimeCost;
    use BuildingRequirements;
    use ResourceRequirements;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var Technology
     */
    protected $technology;

    /**
     * @var Collection
     */
    protected $effects;

    /**
     * @var Collection
     */
    protected $affectedUnits;

    /**
     * @var boolean
     */
    protected $affectAvatar;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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

    /**
     * @return Technology
     */
    public function getTechnology(): Technology
    {
        return $this->technology;
    }

    /**
     * @param Technology $technology
     */
    public function setTechnology(Technology $technology)
    {
        $this->technology = $technology;
    }

    /**
     * @return Collection
     */
    public function getEffects(): Collection
    {
        return $this->effects?: $this->effects = new ArrayCollection();
    }

    /**
     * @param string $effectType
     * @return float
     */
    public function getEffectFactor(string $effectType): float
    {
        $effect = $this->getEffects()->filter(function (TechnologyEffect $technologyEffect) use($effectType) {
           return $technologyEffect->getEffect() === $effectType;
        });

        return $effect->isEmpty()? 0 : $effect->first()->getFactor();
    }

    /**
     * @param Collection $effects
     */
    public function setEffects(Collection $effects)
    {
        $this->effects = $effects;
    }

    /**
     * @return Collection
     */
    public function getAffectedUnits(): Collection
    {
        return $this->affectedUnits;
    }

    /**
     * @param Collection $affectedUnits
     */
    public function setAffectedUnits(Collection $affectedUnits)
    {
        $this->affectedUnits = $affectedUnits;
    }

    /**
     * @return bool
     */
    public function isAffectAvatar(): bool
    {
        return $this->affectAvatar;
    }

    /**
     * @param bool $affectAvatar
     */
    public function setAffectAvatar(bool $affectAvatar)
    {
        $this->affectAvatar = $affectAvatar;
    }


}
