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
 * Class Avatar
 * @package Kori\KingdomServerBundle\Entity
 */
class Avatar
{
    const ALL = 0;
    const WOOD = 1;
    const IRON = 2;
    const CLAY = 3;
    const WHEAT = 4;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $hairColor;

    /**
     * @var int
     */
    protected $beard;

    /**
     * @var int
     */
    protected $ears;

    /**
     * @var int
     */
    protected $eyes;

    /**
     * @var int
     */
    protected $eyebrows;

    /**
     * @var int
     */
    protected $hairStyle;

    /**
     * @var int
     */
    protected $mouth;

    /**
     * @var int
     */
    protected $nose;

    /**
     * @var boolean
     */
    protected $female;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var BattleLog
     */
    protected $battleLog;

    /**
     * @var int
     */
    protected $health;

    /**
     * @var int
     */
    protected $maxHealth;

    /**
     * @var int
     */
    protected $fightingStrength;

    /**
     * @var int
     */
    protected $offBonus;

    /**
     * @var int
     */
    protected $defBonus;

    /**
     * @var int
     */
    protected $resourceBonus;

    /**
     * @var int
     */
    protected $resourceType;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var int
     */
    protected $exp;

    /**
     * @var Town
     */
    protected $town;

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
    public function getHairColor(): int
    {
        return $this->hairColor;
    }

    /**
     * @param int $hairColor
     */
    public function setHairColor(int $hairColor)
    {
        $this->hairColor = $hairColor;
    }

    /**
     * @return int
     */
    public function getBeard(): int
    {
        return $this->beard;
    }

    /**
     * @param int $beard
     */
    public function setBeard(int $beard)
    {
        if(!$this->isFemale())
            $this->beard = $beard;
    }

    /**
     * @return int
     */
    public function getEars(): int
    {
        return $this->ears;
    }

    /**
     * @param int $ears
     */
    public function setEars(int $ears)
    {
        $this->ears = $ears;
    }

    /**
     * @return int
     */
    public function getEyes(): int
    {
        return $this->eyes;
    }

    /**
     * @param int $eyes
     */
    public function setEyes(int $eyes)
    {
        $this->eyes = $eyes;
    }

    /**
     * @return int
     */
    public function getEyebrows(): int
    {
        return $this->eyebrows;
    }

    /**
     * @param int $eyebrows
     */
    public function setEyebrows(int $eyebrows)
    {
        $this->eyebrows = $eyebrows;
    }

    /**
     * @return int
     */
    public function getHairStyle(): int
    {
        return $this->hairStyle;
    }

    /**
     * @param int $hairStyle
     */
    public function setHairStyle(int $hairStyle)
    {
        $this->hairStyle = $hairStyle;
    }

    /**
     * @return int
     */
    public function getMouth(): int
    {
        return $this->mouth;
    }

    /**
     * @param int $mouth
     */
    public function setMouth(int $mouth)
    {
        $this->mouth = $mouth;
    }

    /**
     * @return int
     */
    public function getNose(): int
    {
        return $this->nose;
    }

    /**
     * @param int $nose
     */
    public function setNose(int $nose)
    {
        $this->nose = $nose;
    }

    /**
     * @return bool
     */
    public function isFemale(): bool
    {
        return $this->female;
    }

    /**
     * @param bool $female
     */
    public function setFemale(bool $female)
    {
        $this->female = $female;
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
     * @return BattleLog
     */
    public function getBattleLog(): ?BattleLog
    {
        return $this->battleLog;
    }

    /**
     * @param BattleLog $battleLog
     */
    public function setBattleLog(BattleLog $battleLog)
    {
        $this->battleLog = $battleLog;
    }

    /**
     * @return bool
     */
    public function isAway(): bool
    {
        return !is_null($this->getBattleLog());
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $health
     */
    public function setHealth(int $health)
    {
        $this->health = min($health, $this->getMaxHealth());
    }

    /**
     * @return int
     */
    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    /**
     * @param int $maxHealth
     */
    public function setMaxHealth(int $maxHealth)
    {
        $this->maxHealth = $maxHealth;
    }

    /**
     * @return int
     */
    public function getFightingStrength(): int
    {
        return $this->fightingStrength;
    }

    /**
     * @param int $fightingStrength
     */
    public function setFightingStrength(int $fightingStrength)
    {
        $this->fightingStrength = $fightingStrength;
    }

    /**
     * @return int
     */
    public function getOffBonus(): int
    {
        return $this->offBonus;
    }

    /**
     * @param int $offBonus
     */
    public function setOffBonus(int $offBonus)
    {
        $this->offBonus = $offBonus;
    }

    /**
     * @return int
     */
    public function getDefBonus(): int
    {
        return $this->defBonus;
    }

    /**
     * @param int $defBonus
     */
    public function setDefBonus(int $defBonus)
    {
        $this->defBonus = $defBonus;
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
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp)
    {
        $this->exp = $exp;
    }

    /**
     * @return int
     */
    public function getResourceBonus(): int
    {
        return $this->resourceBonus;
    }

    /**
     * @param int $resourceBonus
     */
    public function setResourceBonus(int $resourceBonus)
    {
        $this->resourceBonus = $resourceBonus;
    }

    /**
     * @return int
     */
    public function getResourceType(): int
    {
        return $this->resourceType;
    }

    /**
     * @param int $resourceType
     */
    public function setResourceType(int $resourceType)
    {
        $this->resourceType = $resourceType;
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

}
