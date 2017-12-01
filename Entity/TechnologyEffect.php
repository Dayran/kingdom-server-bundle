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

/**
 * Class TechnologyEffect
 * @package Kori\KingdomServerBundle\Entity
 */
class TechnologyEffect
{
    const WOOD_MULTIPLIER = "wood_multiplier";
    const CLAY_MULTIPLIER = "clay_multiplier";
    const IRON_MULTIPLIER = "clay_multiplier";
    const WHEAT_MULTIPLIER = "wheat_multiplier";
    const ATTACK_MULTIPLIER = "attack_multiplier";
    const DEFEND_MULTIPLIER = "defend_multiplier";

    /**
     * @var int
     */
    protected $id;

    /**
     * @var TechnologyLevel
     */
    protected $technologyLevel;

    /**
     * @var string
     */
    protected $effect;

    /**
     * @var float
     */
    protected $factor;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return TechnologyLevel
     */
    public function getTechnologyLevel(): TechnologyLevel
    {
        return $this->technologyLevel;
    }

    /**
     * @param TechnologyLevel $technologyLevel
     */
    public function setTechnologyLevel(TechnologyLevel $technologyLevel)
    {
        $this->technologyLevel = $technologyLevel;
    }

    /**
     * @return string
     */
    public function getEffect(): string
    {
        return $this->effect;
    }

    /**
     * @param string $effect
     */
    public function setEffect(string $effect)
    {
        $this->effect = $effect;
    }

    /**
     * @return float
     */
    public function getFactor(): float
    {
        return $this->factor;
    }

    /**
     * @param float $factor
     */
    public function setFactor(float $factor)
    {
        $this->factor = $factor;
    }

}
