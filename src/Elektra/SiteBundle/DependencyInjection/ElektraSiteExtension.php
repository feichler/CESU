<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class ElektraSiteExtension
 *
 * @package Elektra\SiteBundle\DependencyInjection
 *
 *          @version 0.1-dev
 */
class ElektraSiteExtension extends Extension
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