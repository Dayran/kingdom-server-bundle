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

use Kori\KingdomServerBundle\Traits\BuildingRequirements;
use Kori\KingdomServerBundle\Traits\EffectiveUpgrades;
use Kori\KingdomServerBundle\Traits\Resources;
use Kori\KingdomServerBundle\Traits\TimeCost;

/**
 * Class Unit
 * @package Kori\KingdomServerBundle\Entity
 */
class Unit
{

    use EffectiveUpgrades;
    use Resources;
    use TimeCost;
    use BuildingRequirements;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Race
     */
    protected $race;

    /**
     * @var int
     */
    protected $attack = 0;

    /**
     * @var int
     */
    protected $defenseInfantry = 0;

    /**
     * @var int
     */
    protected $defenseCavalry = 0;

    /**
     * @var int
     */
    protected $speed = 0;

    /**
     * @var bool
     */
    protected $cavalry = false;

    /**
     * @var int
     */
    protected $carry = 0;

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
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param Race $race
     */
    public function setRace(Race $race)
    {
        $this->race = $race;
    }

    /**
     * @return int
     */
    public function getAttack(): int
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     */
    public function setAttack(int $attack)
    {
        $this->attack = $attack;
    }

    /**
     * @return int
     */
    public function getDefenseInfantry(): int
    {
        return $this->defenseInfantry;
    }

    /**
     * @param int $defenseInfantry
     */
    public function setDefenseInfantry(int $defenseInfantry)
    {
        $this->defenseInfantry = $defenseInfantry;
    }

    /**
     * @return int
     */
    public function getDefenseCavalry(): int
    {
        return $this->defenseCavalry;
    }

    /**
     * @param int $defenseCavalry
     */
    public function setDefenseCavalry(int $defenseCavalry)
    {
        $this->defenseCavalry = $defenseCavalry;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     */
    public function setSpeed(int $speed)
    {
        $this->speed = $speed;
    }

    /**
     * @return bool
     */
    public function isCavalry(): bool
    {
        return $this->cavalry;
    }

    /**
     * @param bool $cavalry
     */
    public function setCavalry(bool $cavalry)
    {
        $this->cavalry = $cavalry;
    }

    /**
     * @return int
     */
    public function getCarry(): int
    {
        return $this->carry;
    }

    /**
     * @param int $carry
     */
    public function setCarry(int $carry)
    {
        $this->carry = $carry;
    }

}
