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
 * Class BuildingType
 * @package Kori\KingdomServerBundle\Entity
 */
class BuildingType
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
     * @var Collection
     */
    protected $levels;

    /**
     * @var bool
     */
    protected $uniqueInBase = true;

    /**
     * @var Collection
     */
    protected $technologies;

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
     * @return Collection
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    /**
     * @param Collection $levels
     */
    public function setLevels(Collection $levels)
    {
        $this->levels = $levels;
    }

    /**
     * @return bool
     */
    public function isUniqueInBase(): bool
    {
        return $this->uniqueInBase;
    }

    /**
     * @param bool $uniqueInBase
     */
    public function setUniqueInBase(bool $uniqueInBase)
    {
        $this->uniqueInBase = $uniqueInBase;
    }

    /**
     * @return Collection
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies?: $this->technologies = new ArrayCollection();
    }

    /**
     * @param Collection $technologies
     */
    public function setTechnologies(Collection $technologies)
    {
        $this->technologies = $technologies;
    }

}
