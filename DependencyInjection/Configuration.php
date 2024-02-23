<?php

namespace tbn\BoilerplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('boilerplate');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
        ->children()
            ->arrayNode('templates')
                ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('name')->end()
                        ->arrayNode('renderers')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('folder')->end()
                                    ->scalarNode('template')->end()
                                    ->scalarNode('suffix')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
