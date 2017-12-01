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
 * Class ResearchLog
 * @package Kori\KingdomServerBundle\Entity
 */
class ResearchLog
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Town
     */
    protected $town;

    /**
     * @var TechnologyLevel
     */
    protected $technologyLevel;

    /**
     * @var int
     */
    protected $ttc;

    /**
     * @var bool
     */
    protected $boosted;

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
    public function getTown(): Town
    {
        return $this->town;
    }

    /**
     * @param Town $town
     */
    public function setTown(Town $town)
    {
        $this->town = $town;
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
     * @return int
     */
    public function getTtc(): int
    {
        return $this->ttc;
    }

    /**
     * @param int $ttc
     */
    public function setTtc(int $ttc)
    {
        $this->ttc = $ttc;
    }

    /**
     * @return bool
     */
    public function isBoosted(): bool
    {
        return $this->boosted;
    }

    /**
     * @param bool $boosted
     */
    public function setBoosted(bool $boosted)
    {
        $this->boosted = $boosted;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->getTtc() <= time() || $this->isBoosted();
    }


}
