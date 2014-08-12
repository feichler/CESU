<?php

namespace Elektra\SeedBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{

    protected $bundleName = 'ElektraSeedBundle';

    protected $routes = array(
        'seedunits'                => 'SeedUnit/SeedUnit',
        'seedunits_models'         => 'SeedUnit/SeedUnitModel',
        'seedunits_powerCordTypes' => 'SeedUnit/SeedUnitPowerCordType',
    );

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {

        $collection = new RouteCollection();

        foreach ($this->routes as $namePart => $controllerPart) {
            $path       = str_replace('_', '/', $namePart) . '/';
            $route      = $this->bundleName . '_' . $namePart . '_';
            $controller = $this->bundleName . ':' . $controllerPart . ':';

            $this->addBrowseRoute($collection, $path, $route, $controller);
            $this->addViewRoute($collection, $path, $route, $controller);
            $this->addAddRoute($collection, $path, $route, $controller);
            $this->addEditRoute($collection, $path, $route, $controller);
            $this->addDeleteRoute($collection, $path, $route, $controller);
        }

        return $collection;
    }

    protected function addBrowseRoute(RouteCollection $collection, $path, $route, $controller)
    {

        $browseRoute = new Route($path . '{page}');
        $browseRoute->setDefault('_controller', $controller . 'browse');
        $browseRoute->setDefault('page', 1);
        $browseRoute->setRequirement('page', '\d+');
        $collection->add($route . 'browse', $browseRoute);
    }

    protected function addViewRoute(RouteCollection $collection, $path, $route, $controller)
    {

        $viewRoute = new Route($path . 'view/{id}');
        $viewRoute->setDefault('_controller', $controller . 'view');
        $viewRoute->setRequirement('id', '\d+');
        $collection->add($route . 'view', $viewRoute);
    }

    protected function addAddRoute(RouteCollection $collection, $path, $route, $controller)
    {

        $addRoute = new Route($path . 'add');
        $addRoute->setDefault('_controller', $controller . 'add');
        $collection->add($route . 'add', $addRoute);
    }

    protected function addEditRoute(RouteCollection $collection, $path, $route, $controller)
    {

        $editRoute = new Route($path . 'edit/{id}');
        $editRoute->setDefault('_controller', $controller . 'edit');
        $editRoute->setRequirement('id', '\d+');
        $collection->add($route . 'edit', $editRoute);
    }

    protected function addDeleteRoute(RouteCollection $collection, $path, $route, $controller)
    {

        $deleteRoute = new Route($path . 'delete/{id}');
        $deleteRoute->setDefault('_controller', $controller . 'delete');
        $deleteRoute->setRequirement('id', '\d+');
        $collection->add($route . 'delete', $deleteRoute);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {

        return 'routing' === $type;
    }
}