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
use Kori\KingdomServerBundle\Traits\CreatedAt;

/**
 * Class Alliance
 * @package Kori\KingdomServerBundle\Entity
 */
class Alliance
{

    use CreatedAt;

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
    protected $kingdoms;

    /**
     * @var Collection
     */
    protected $kings;

    /**
     * @var Collection
     */
    protected $dukes;

    /**
     * @var boolean
     */
    protected $secret;

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
    public function getKingdoms(): Collection
    {
        return $this->kingdoms;
    }

    /**
     * @param Collection $kingdoms
     */
    public function setKingdoms(Collection $kingdoms)
    {
        $this->kingdoms = $kingdoms;
    }

    /**
     * @return Collection
     */
    public function getKings(): Collection
    {
        return $this->kings;
    }

    /**
     * @param Collection $kings
     */
    public function setKings(Collection $kings)
    {
        $this->kings = $kings;
    }

    /**
     * @return Collection
     */
    public function getDukes(): Collection
    {
        return $this->dukes;
    }

    /**
     * @param Collection $dukes
     */
    public function setDukes(Collection $dukes)
    {
        $this->dukes = $dukes;
    }

    /**
     * @return bool
     */
    public function isSecret(): bool
    {
        return $this->secret;
    }

    /**
     * @param bool $secret
     */
    public function setSecret(bool $secret)
    {
        $this->secret = $secret;
    }

}
