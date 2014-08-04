<?php

namespace Elektra\SeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ElektraSeedExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $processor     = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $this->parseParameters($container, $config, 'elektra_seed');
    }

    private function parseParameters(ContainerBuilder $container, array $config, $pathPrefix = '')
    {

        foreach ($config as $key => $value) {
            $path = $pathPrefix . '.' . $key;
            $container->setParameter($path, $value);
            if (is_array($value)) {
                $this->parseParameters($container, $value, $path);
            }
        }
    }

    public function getXsdValidationBasePath()
    {

        return __DIR__ . '/../Resources/config/';
    }

    public function getNamespace()
    {

        return 'http://www.example.com/symfony/schema/';
    }
}