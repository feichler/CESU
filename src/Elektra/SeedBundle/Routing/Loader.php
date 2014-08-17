<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Routing;

use Elektra\SiteBundle\Navigator\Definition;
use Elektra\SiteBundle\Navigator\Navigator;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Loader
 *
 * @package Elektra\SeedBundle\Routing
 *
 * @version 0.1-dev
 */
class Loader extends BaseLoader
{

    /**
     * Name of the containing bundle. Used as prefix for the generated routes
     *
     * @var string
     */
    protected $bundleName = 'ElektraSeedBundle';

    /**
     * @var Navigator
     */
    protected $navigator;

    /**
     * @param Navigator $navigator
     */
    public function __construct(Navigator $navigator)
    {

        $this->navigator = $navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {

        $collection = new RouteCollection();

        foreach ($this->navigator->getDefinitions() as $definition) {

            $routes = array(
                'browse' => $this->getBrowseRoute($definition),
                'view'   => $this->getViewRoute($definition),
                'add'    => $this->getAddRoute($definition),
                'edit'   => $this->getEditRoute($definition),
                'delete' => $this->getDeleteRoute($definition),
            );

            foreach ($routes as $name => $route) {
                $route->setPath($route->getPath() . '{slash}');
                $route->setDefault('slash','/');
                $route->setRequirement('slash', '[/]{0,1}');
                $collection->add($definition->getRouteNamePrefix() . '_' . $name, $route);
            }
        }

        return $collection;
    }

    /**
     * @param Definition $definition
     *
     * @return Route
     */
    private function getBrowseRoute(Definition $definition)
    {

        $route = new Route($definition->getRoutePath() . '/{page}');
        $route->setDefault('_controller', $definition->getControllerName() . ':browse');
        $route->setDefault('page', 1);
        $route->setRequirement('page', '\d+');

        return $route;
    }

    /**
     * @param Definition $definition
     *
     * @return Route
     */
    private function getViewRoute(Definition $definition)
    {

        $route = new Route($definition->getRoutePath() . '/view/{id}');
        $route->setDefault('_controller', $definition->getControllerName() . ':view');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    /**
     * @param Definition $definition
     *
     * @return Route
     */
    private function getAddRoute(Definition $definition)
    {

        $route = new Route($definition->getRoutePath() . '/add');
        $route->setDefault('_controller', $definition->getControllerName() . ':add');

        return $route;
    }

    /**
     * @param Definition $definition
     *
     * @return Route
     */
    private function getEditRoute(Definition $definition)
    {

        $route = new Route($definition->getRoutePath() . '/edit/{id}');
        $route->setDefault('_controller', $definition->getControllerName() . ':edit');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    /**
     * @param Definition $definition
     *
     * @return Route
     */
    private function getDeleteRoute(Definition $definition)
    {

        $route = new Route($definition->getRoutePath() . '/delete/{id}');
        $route->setDefault('_controller', $definition->getControllerName() . ':delete');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {

        return 'routing' === $type;
    }
}