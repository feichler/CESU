<?php

namespace Elektra\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ElektraUserExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {

        // load and process the configuration - only useful if a configuration tree is defined
        $processor     = new Processor();
        $configuration = new Configuration();
        $newConfig     = $processor->processConfiguration($configuration, $config);

        // NOTE: if configuration tree is defined, the $newConfig needs to be parsed and stored in the container

        // load the configuration files from the bundle
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}