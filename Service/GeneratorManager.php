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

use Kori\KingdomServerBundle\Generator\GeneratorInterface;

/**
 * Class GeneratorManager
 * @package Kori\KingdomServerBundle\Service
 */
final class GeneratorManager
{
    /**
     * @var array
     */
    protected $generators = [];

    /**
     * Adds a generator to the pool
     *
     * @param GeneratorInterface $generator
     * @return bool
     */
    public function addGenerator(GeneratorInterface $generator): bool
    {
        $className = get_class($generator);
        //Checks if generator already exists or if there is a similar name
        if($this->findGenerator($className) === null)
        {
            $this->generators[$className] = $generator;
            return true;
        }
        return false;

    }

    /**
     * Finds the generator by provided name
     *
     * @param string $name
     * @return null|GeneratorInterface
     */
    public function findGenerator(string $name): ?GeneratorInterface
    {
        if(array_key_exists($name,$this->generators))
            return $this->generators[$name];

        //If is a class name explode and grab the last portion
        $pieces = explode("\\",$name);
        $actualName = end($pieces);

        if(strcasecmp(substr($actualName, -9), "generator") != 0)
            $actualName .= "generator";

        foreach(array_keys($this->generators) as $key)
        {
            if(stripos($key,'\\'.$actualName) !== false)
                return $this->generators[$key];
        }
        return null;
    }
}
