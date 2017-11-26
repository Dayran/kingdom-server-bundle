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

/**
 * Class Account
 * @package Kori\KingdomServerBundle\Entity
 */
class Account
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $protection;

    /**
     * @var int
     */
    protected $gold = 0;

    /**
     * @var int
     */
    protected $silver = 0;

    /**
     * @var Kingdom
     */
    protected $kingdom;

    /**
     * @var int
     */
    protected $premiumEnd = 0;

    /**
     * @var Avatar
     */
    protected $avatar;

    /**
     * @var Collection
     */
    protected $towns;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getProtection(): int
    {
        return $this->protection;
    }

    /**
     * @param int $protection
     */
    public function setProtection(int $protection)
    {
        $this->protection = $protection;
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return $this->getProtection() > time();
    }

    /**
     * @return int
     */
    public function getGold(): int
    {
        return $this->gold;
    }

    /**
     * @param int $gold
     */
    public function setGold(int $gold)
    {
        $this->gold = $gold;
    }

    /**
     * @return int
     */
    public function getSilver(): int
    {
        return $this->silver;
    }

    /**
     * @param int $silver
     */
    public function setSilver(int $silver)
    {
        $this->silver = $silver;
    }

    /**
     * @return Kingdom
     */
    public function getKingdom(): Kingdom
    {
        return $this->kingdom;
    }

    /**
     * @param Kingdom $kingdom
     */
    public function setKingdom(Kingdom $kingdom)
    {
        $this->kingdom = $kingdom;
    }

    /**
     * @return int
     */
    public function getPremiumEnd(): int
    {
        return $this->premiumEnd;
    }

    /**
     * @param int $premiumEnd
     */
    public function setPremiumEnd(int $premiumEnd)
    {
        $this->premiumEnd = $premiumEnd;
    }

    /**
     * @return bool
     */
    public function isPremium(): bool
    {
        return $this->getPremiumEnd() > time();
    }

    /**
     * @return Avatar
     */
    public function getAvatar(): Avatar
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
    public function getTowns(): Collection
    {
        return $this->towns?: $this->towns = new ArrayCollection();
    }

    /**
     * @param Collection $towns
     */
    public function setTowns(Collection $towns)
    {
        $this->towns = $towns;
    }

}
