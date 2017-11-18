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
use Kori\KingdomServerBundle\Rules\AttackRuleInterface;
use Kori\KingdomServerBundle\Rules\BuildRuleInterface;


/**
 * Class RuleManager
 * @package Kori\KingdomServerBundle\Service
 */
final class RuleManager
{

    /**
     * @var array
     */
    protected $buildRules = [];

    /**
     * @var array
     */
    protected $attackRules = [];

    /**
     * Adds a build rule to the pool
     *
     * @param BuildRuleInterface $buildRule
     * @return bool
     */
    public function addBuildRule(BuildRuleInterface $buildRule): bool
    {
        $className = get_class($buildRule);
        if(!array_key_exists($className, $this->buildRules))
        {
            $this->buildRules[$className] = $buildRule;
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return BuildRuleInterface|null
     */
    public function getBuildRule(string $name): ?BuildRuleInterface
    {
        return $this->retrieveByNameFromArray($this->buildRules, $name);
    }

    /**
     * @return array
     */
    public function getBuildRules(): array
    {
        return $this->buildRules;
    }

    /**
     * Adds a attack rule to the pool
     *
     * @param AttackRuleInterface $attackRule
     * @return bool
     */
    public function addAttackRule(AttackRuleInterface $attackRule): bool
    {
        $className = get_class($attackRule);
        if(!array_key_exists($className, $this->attackRules))
        {
            $this->attackRules[$className] = $attackRule;
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return AttackRuleInterface|null
     */
    public function getAttackRule(string $name): ?AttackRuleInterface
    {
        return $this->retrieveByNameFromArray($this->attackRules, $name);
    }

    /**
     * @return array
     */
    public function getAttackRules(): array
    {
        return $this->attackRules;
    }

    /**
     * @param array $holder
     * @param string $name
     * @return mixed|null
     */
    protected function retrieveByNameFromArray(array &$holder, string $name)
    {
        if(array_key_exists($name,$holder))
            return $holder[$name];

        //If is a class name explode and grab the last portion
        $pieces = explode("\\",$name);
        $actualName = end($pieces);

        foreach(array_keys($holder) as $key)
        {
            if(stripos($key,'\\'.$actualName) !== false)
                return $holder[$key];
        }
        return null;
    }
}
