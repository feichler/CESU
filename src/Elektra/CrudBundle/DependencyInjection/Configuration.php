<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\CrudBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Elektra\CrudBundle\DependencyInjection
 *
 * @version 0.1-dev
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('elektra_crud');

        // NOTE: configuration options for the crud bundle needed?

        return $treeBuilder;
    }
}
