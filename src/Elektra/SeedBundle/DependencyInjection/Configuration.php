<?php

namespace Elektra\SeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('elektra_seed');

        // NOTE: configuration options for the seed bundle needed?
        // NOTE: list limit configuration in cleanup folder

        return $treeBuilder;
    }
}
