<?php

namespace Elektra\SeedBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('elektra_seed');

        $rootNode->children()
            ->arrayNode('lists')
                ->addDefaultsIfNotSet()
                ->canBeUnset()
                ->children()
                    ->arrayNode('limits')
                        ->addDefaultsIfNotSet()
                        ->canBeUnset()
                        ->children()
                            ->scalarNode('unit_model')->defaultNull()->end()
                            ->scalarNode('unit_power')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }


}