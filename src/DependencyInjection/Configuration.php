<?php

namespace Tbn\BoilerplateBundle\DependencyInjection;

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
            ->scalarNode('project_dir')
                ->defaultValue('/srv/app')
            ->end()
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
                                    ->scalarNode('prefix')->defaultValue('')->end()
                                    ->scalarNode('extension')->defaultValue('php')->end()
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
