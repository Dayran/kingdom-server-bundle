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

namespace Kori\KingdomServerBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kori\KingdomServerBundle\Entity\Avatar;
use Kori\KingdomServerBundle\Rules\EffectRuleInterface;

/**
 * Class EffectManager
 * @package Kori\KingdomServerBundle\Service
 */
final class EffectManager
{
    /**
     * @var Collection
     */
    protected $effectRules;

    public function __construct()
    {
        $this->effectRules = new ArrayCollection();
    }

    /**
     * @param EffectRuleInterface $effectRule
     * @return bool
     */
    public function addEffectRule(EffectRuleInterface $effectRule): bool
    {
        if(!$this->effectRules->contains($effectRule)) {
            $this->effectRules->add($effectRule);
            return true;
        }
        return false;
    }

    /**
     * @param Avatar $avatar
     * @param int $type
     * @param int $value
     */
    public function process(Avatar $avatar, int $type, int $value)
    {
        $this->effectRules->filter(function (EffectRuleInterface $rule) use($avatar, $type, $value) {
            $rule->apply($avatar, $type, $value);
        });
    }
}
