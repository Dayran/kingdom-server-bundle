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

namespace Kori\KingdomServerBundle\Generator;


use Kori\KingdomServerBundle\Entity\Field;
use Kori\KingdomServerBundle\Service\Server;

/**
 * Class SampleWorldGenerator
 * @package Kori\KingdomServerBundle\Generator
 */
class SampleWorldGenerator implements GeneratorInterface
{
    const FIELD = 1;
    const OASIS = 2;

    public function generate(Server $server): bool
    {
        $em = $server->getEntityManager();
        $totalExpected = 0;
        for($x = -20; $x <= 20; $x++)
        {
            for($y = -20; $y <= 20; $y++)
            {
                $field = new Field();
                $field->setPosX($x);
                $field->setPosY($y);
                $rand = (float)rand()/(float)getrandmax();

                //10% chance of being an oasis
                if ($rand < 0.1)
                    $field->setType(self::OASIS);
                else
                    $field->setType(self::FIELD);

                $em->persist($field);
                $totalExpected++;
            }
            $em->flush();
            $em->clear();
        }

        //Checks if total fields created is correct
        $fieldManager = $server->getFieldManager();
        $totalCreated = $fieldManager->totalCount();
        if($totalCreated !== $totalExpected)
            return false;
        //Start adjusting percentage

        //There should be at least 10oasis
        if($fieldManager->totalCount(self::OASIS) / $totalCreated < 0.1)
        {
            $missing = round($totalExpected * 0.1 - $fieldManager->totalCount(self::OASIS));
            $replace = array_rand($fieldManager->getFields(self::FIELD), $missing);
            foreach($replace as $r)
            {
                $r->setType(self::OASIS);
                $em->persist($r);
                $em->flush();
            }
        }
        //End adjust percentage

        return true;
    }

    public function getType(): int
    {
        return self::WORLD;
    }

}
