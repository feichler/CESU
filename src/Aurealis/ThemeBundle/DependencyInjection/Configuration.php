<?php

namespace Aurealis\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('aurealis_theme');

        $rootNode->children()
            ->arrayNode('lists')
                ->addDefaultsIfNotSet()
                ->canBeUnset()
                ->children()
                    ->scalarNode('limit')->defaultValue(25)->end()
                    ->arrayNode('options')
                        ->canBeUnset()
                        ->defaultValue(array(5,10,25,50,100))
                        ->prototype('scalar')
                            ->validate()
                                ->ifTrue(function($v){return !is_numeric($v); })
                                ->thenInvalid('%s is not a number')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }


}