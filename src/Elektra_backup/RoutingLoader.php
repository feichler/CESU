<?php

namespace Elektra\CrudBundle\Navigator;

use Elektra\CrudBundle\Definition\Definition;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{

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
    public function supports($resource, $type = null)
    {

        return 'routing' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {

        $collection = new RouteCollection();

        $actions = array('browse', 'add', 'view', 'edit', 'delete');

        foreach ($this->navigator->getDefinitions() as $definition) {
            if ($definition instanceof Definition) {
                foreach ($actions as $action) {
                    $collection->add($definition->getPrefixRoute() . '_' . $action, $this->getRoute($definition, $action));
                }
            }
        }

        return $collection;
    }

    /**
     * @param Definition $definition
     * @param string     $action
     *
     * @return Route
     */
    protected function getRoute(Definition $definition, $action)
    {

        $path = $definition->getPath();
        if ($action != 'browse') {
            $path .= '/' . $action;
            if ($action != 'add') {
                $path .= '/{id}';
            }
        } else {
            $path .= '/{page}';
        }
        $path .= '{slash}';

        $route = new Route($path);
        $route->setDefault('_controller', $definition->getController() . ':' . $action);
        $route->setDefault('slash', '/');
        $route->setRequirement('slash', '[/]{0,1}');

        if ($action == 'browse') {
            $route->setDefault('page', 1);
            $route->setRequirement('page', '\d+');
        } else if ($action != 'add') {
            $route->setRequirement('id', '\d+');
        }

        return $route;
    }
}