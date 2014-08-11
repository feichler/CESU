<?php

namespace Elektra\SiteBundle\DependencyInjection;

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

        $rootNode    = $treeBuilder->root('elektra_site');

//        $rootNode->children()->scalarNode('test');


//        $stringsNode = $rootNode->children()->arrayNode('strings');
//        $stringsNode->addDefaultsIfNotSet();
//        $stringsNode->cannotBeEmpty();
//
//        $adminStrings = $stringsNode->children()->arrayNode('admin');
//        $adminStrings->addDefaultsIfNotSet();
//        $adminStrings->cannotBeEmpty();
//        $adminStrings->children()->scalarNode('page_name')->defaultValue('CESU Administration')->end();
//        $adminStrings->children()->scalarNode('page_title')->defaultValue('Administration')->end();
//
//        $adminStrings->end();
//
//        $requestStrings = $stringsNode->children()->arrayNode('request');
//        $requestStrings->addDefaultsIfNotSet();
//        $requestStrings->cannotBeEmpty();
//        $requestStrings->children()->scalarNode('page_title')->defaultValue('Request Page')->end();
//
//        $requestStrings->end();
//
//        $stringsNode->end();

        // NOTE: add any configuration options?

        return $treeBuilder;
    }
}