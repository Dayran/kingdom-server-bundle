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

namespace Kori\KingdomServerBundle\Tests\Units\DependencyInjection\Compiler;


use atoum\test;
use Kori\KingdomServerBundle\DependencyInjection\Compiler\AddAttackRuleCompilerPass as TestedModel;
use Kori\KingdomServerBundle\Tests\Rules\ServerAware;

/**
 * Class AddAttackRuleCompilerPass
 * @package Kori\KingdomServerBundle\Tests\Units\DependencyInjection\Compiler
 */
class AddAttackRuleCompilerPass extends test
{

    public function testPass()
    {

        $taggedServicesResult = ["ServerAware"];

        $this
            ->given($containerBuilder = new \mock\Symfony\Component\DependencyInjection\ContainerBuilder())
            ->and($definition = new \mock\Symfony\Component\DependencyInjection\Definition(ServerAware::class))
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->shuntParentClassCalls())
            ->and($manager = new \mock\Symfony\Component\DependencyInjection\Definition())
            ->and($this->calling($containerBuilder)->getAlias = function ($alias) {
                return $alias;
            })
            ->and($this->calling($containerBuilder)->getDefinition = function ($name) use ($definition, $manager) {
                if($name == "ServerAware")
                    return $definition;
                return $manager;
            })
            ->and($this->calling($containerBuilder)->findTaggedServiceIds = function () use ($taggedServicesResult) {
                return $taggedServicesResult;
            })
            ->and($compiler = new TestedModel())
            ->when($compiler->process($containerBuilder))
            ->then(
                $this->mock($containerBuilder)->call('getDefinition')->withArguments('kori_kingdom.rule_manager')->exactly(2)
                    ->and($this->mock($containerBuilder)->call('findTaggedServiceIds')->withArguments('kori_kingdom.attack_rule')->exactly(1))

                    ->and($this->mock($containerBuilder)->call('getDefinition')->withArguments('ServerAware')->exactly(1))

                    //it calls addSpread three times
                    ->and($this->mock($definition)->call('addMethodCall')->withArguments('setServer')->exactly(1))
                    ->and($this->mock($manager)->call('addMethodCall')->withArguments('addAttackRule')->exactly(1))
            )
        ;
    }

}
