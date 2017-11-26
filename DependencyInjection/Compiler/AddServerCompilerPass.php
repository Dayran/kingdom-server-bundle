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

namespace Kori\KingdomServerBundle\DependencyInjection\Compiler;

use Kori\KingdomServerBundle\Service\Server;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AddServerCompilerPass
 * @package Kori\KingdomServerBundle\DependencyInjection\Compiler
 */
class AddServerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $manger = $container->getDefinition('kori_kingdom.server_manager');

        $defaultRules = $container->getParameter('kori_kingdom.default_rules');
        $ruleManager = $container->getDefinition('kori_kingdom.rule_manager');
        $effectManager = $container->getDefinition('kori_kingdom.effect_manager');

        foreach($container->getParameter('kori_kingdom.servers') as $name => $config)
        {
            $definition = new Definition(Server::class);
            $definition->setArguments([new Reference(str_replace('@', '',$config['db_connection'])), $config['rate'], $config['days_of_protection'], $defaultRules]);
            $definition->addMethodCall('setEffectManager', [$effectManager]);
            $definition->addMethodCall('setRuleManager', [$ruleManager]);

            $manger->addMethodCall('addServer', [$name, $config['domain'], $definition]);
        }
    }
}
