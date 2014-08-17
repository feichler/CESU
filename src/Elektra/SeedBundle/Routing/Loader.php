<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Routing;

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
     * Information needed to generated the different routes
     *
     * @var array
     */
    protected $routes = array(
        'SeedUnit'                           => 'SeedUnits/SeedUnit',
        'Trainings_Registration'             => 'Trainings/Registration',
        'Trainings_Attendance'               => 'Trainings/Attendance',
        'MasterData_SeedUnits_Model'         => 'SeedUnits/SeedUnitModel',
        'MasterData_SeedUnits_PowerCordType' => 'SeedUnits/SeedUnitPowerCordType',
        'MasterData_Trainings_Training'      => 'Trainings/Training',
        'MasterData_Geographic_Country'      => 'Companies/Country',
        'MasterData_Geographic_Region'       => 'Companies/Region',
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

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    private function getBrowseRoute($path, $controller)
    {

        $route = new Route($path . '{page}');
        $route->setDefault('_controller', $controller . 'browse');
        $route->setDefault('page', 1);
        $route->setRequirement('page', '\d+');

        return $route;
    }

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    private function getViewRoute($path, $controller)
    {

        $route = new Route($path . 'view/{id}');
        $route->setDefault('_controller', $controller . 'view');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    private function getAddRoute($path, $controller)
    {

        $route = new Route($path . 'add');
        $route->setDefault('_controller', $controller . 'add');

        return $route;
    }

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    private function getEditRoute($path, $controller)
    {

        $route = new Route($path . 'edit/{id}');
        $route->setDefault('_controller', $controller . 'edit');
        $route->setRequirement('id', '\d+');

        return $route;
    }

    /**
     * @param string $path
     * @param string $controller
     *
     * @return Route
     */
    private function getDeleteRoute($path, $controller)
    {

        $route = new Route($path . 'delete/{id}');
        $route->setDefault('_controller', $controller . 'delete');
        $route->setRequirement('id', '\d+');

        return $route;
    }
}