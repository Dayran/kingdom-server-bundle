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

class Kingdom
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Account
     */
    protected $king;

    /**
     * @var Collection
     */
    protected $governors;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Account
     */
    public function getKing(): Account
    {
        return $this->king;
    }

    /**
     * @param Account $king
     */
    public function setKing(Account $king)
    {
        $this->king = $king;
    }

    /**
     * @return Collection
     */
    public function getGovernors(): Collection
    {
        return $this->governors?: $this->governors = new ArrayCollection();
    }

    /**
     * @param Collection $governors
     */
    public function setGovernors(Collection $governors)
    {
        $this->governors = $governors;
    }

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields?: $this->fields = new ArrayCollection();
    }

    /**
     * @param Collection $fields
     */
    public function setFields(Collection $fields)
    {
        $this->fields = $fields;
    }

    public function onPostLoad()
    {
        //To remove king from governor collection
        if($this->getGovernors()->contains($this->getKing()))
            $this->getGovernors()->remove($this->getKing());
    }


}
