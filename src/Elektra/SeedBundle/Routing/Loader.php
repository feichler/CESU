<?php
namespace Elektra\SeedBundle\Routing;

use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Loader extends BaseLoader
{

    /**
     * Name of the containing bundle. Used as prefix for the generated routes
     *
     * @var string
     */
    protected $bundleName = 'ElektraSeedBundle';

    /**
     * Information needed to generated the different routes
     *
     * @var array
     */
    protected $routes = array(
        'SeedUnits'                          => 'SeedUnits/SeedUnit',
        'MasterData_SeedUnits_Model'         => 'SeedUnits/SeedUnitModel',
        'MasterData_SeedUnits_PowerCordType' => 'SeedUnits/SeedUnitPowerCordType',
        'MasterData_Geographic_Country'      => 'Companies/Country',
    );

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {

        $collection = new RouteCollection();

        foreach ($this->routes as $namePart => $controllerPart) {
            $path = str_replace('_', '/', $namePart) . '/';
            //            $route      = $this->bundleName . '_' . strtolower($namePart) . '_';
            $route      = $this->bundleName . '_' . $namePart . '_';
            $controller = $this->bundleName . ':' . $controllerPart . ':';

            $collection->add($route . 'browse', $this->getBrowseRoute($path, $controller));
            $collection->add($route . 'view', $this->getViewRoute($path, $controller));
            $collection->add($route . 'add', $this->getAddRoute($path, $controller));
            $collection->add($route . 'edit', $this->getEditRoute($path, $controller));
            $collection->add($route . 'delete', $this->getDeleteRoute($path, $controller));
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {

        return 'routing' === $type;
    }

    private function getBrowseRoute($path, $controller)
    {

        $route = new Route($path . '{page}');
        $route->setDefault('_controller', $controller . 'browse');
        $route->setDefault('page', 1);
        $route->setRequirement('page', '\d+');

        return $route;
    }

    private function getViewRoute($path, $controller)
    {

        $route = new Route($path . 'view/{id}');
        $route->setDefault('_controller', $controller . 'view');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    private function getAddRoute($path, $controller)
    {

        $route = new Route($path . 'add');
        $route->setDefault('_controller', $controller . 'add');

        return $route;
    }

    private function getEditRoute($path, $controller)
    {

        $route = new Route($path . 'edit/{id}');
        $route->setDefault('_controller', $controller . 'edit');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    private function getDeleteRoute($path, $controller)
    {

        $route = new Route($path . 'delete/{id}');
        $route->setDefault('_controller', $controller . 'delete');
        $route->setRequirement('id', '\d+');

        return $route;
    }
}