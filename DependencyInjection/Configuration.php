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

namespace Kori\KingdomServerBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kori_kingdom_server');

        $rootNode
            ->children()
                ->arrayNode('default_rules')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('build')
                        ->isRequired()
                        ->end()
                        ->scalarNode('attack')->isRequired()->end()
                        ->scalarNode('influence')->isRequired()->end()
                    ->end()
                ->end()
            ->end();

        $rootNode
            ->children()
                ->arrayNode('servers')
                    ->isRequired()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('domain')->end()
                            ->scalarNode('db_connection')->end()
                            ->scalarNode('rate')
                                ->defaultValue(1)
                            ->end()
                            ->scalarNode('days_of_protection')
                                ->defaultValue(7)
                            ->end()
                            ->arrayNode('rules')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->arrayNode('build')
                                    ->end()
                                    ->scalarNode('attack')->end()
                                    ->scalarNode('influence')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }


}
