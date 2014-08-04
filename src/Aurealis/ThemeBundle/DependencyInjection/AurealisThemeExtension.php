<?php

namespace Aurealis\ThemeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AurealisThemeExtension extends Extension
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

        $this->parseParameters($container, $config, 'aurealis_theme');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');


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