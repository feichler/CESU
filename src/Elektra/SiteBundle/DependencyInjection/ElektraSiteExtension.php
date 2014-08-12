<?php

namespace Elektra\SiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ElektraSiteExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
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
        $config        = $processor->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
//        $loader->load('strings.yml');
//
////        $container->get('translator');
//
////        $loader = new \Symfony\Component\Translation\Loader\YamlFileLoader();
////        $loader->load(new Yam)
//
//        // process the parameters from site.yml
//        $parameters = $container->getParameter('elektra_site');
//
//        // language strings
//        $strings = $parameters['strings'];
//        $this->parseStrings($container, $strings);
    }

//    protected function parseStrings(ContainerBuilder $container, array $strings, $prefix = 'site_lang')
//    {
//
//        foreach ($strings as $key => $value) {
//            $path = $prefix . '.' . $key;
//            if (is_string($value)) {
//                $container->setParameter($path, $value);
//            } else if (is_array($value)) {
//                $this->parseStrings($container, $value, $path);
//            } else {
//                throw new \InvalidArgumentException('Invalid value for strings');
//            }
//        }
//    }
}