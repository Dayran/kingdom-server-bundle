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

namespace Kori\KingdomServerBundle\Tests\Units\Rules\Influence;


use atoum\test;
use Kori\KingdomServerBundle\Entity\Field;
use Kori\KingdomServerBundle\Rules\Influence\InverseSquare as TestedModel;

/**
 * Class InverseSquare
 * @package Kori\KingdomServerBundle\Tests\Units\Rules\Influence
 */
class InverseSquare extends test
{

    public function testFactor()
    {
        $from = new Field();
        $from->setPosX(2);
        $from->setPosY(2);

        $to = new Field();
        $to->setPosX(0);
        $to->setPosY(0);

        $next = new Field();
        $next->setPosX(1);
        $next->setPosY(0);

        $alternate = new Field();
        $alternate->setPosX(1);
        $alternate->setPosY(3);

        $this
            ->given($rule = new TestedModel())
            ->when($factor = $rule->factor($to, $from))
            ->then(
                $this->float($factor)->isNearlyEqualTo(12.5)
            )
            ->when($factor = $rule->factor($to, $alternate))
            ->then(
                $this->float($factor)->isNearlyEqualTo(10)
            )
            ->when($factor = $rule->factor($to, $next))
            ->then(
                $this->float($factor)->isNearlyEqualTo(100)
            )
            ->when($factor = $rule->factor($to, $to))
            ->then(
                $this->float($factor)->isNearlyEqualTo(100)
            )
        ;
    }
}
