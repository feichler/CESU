<?php

namespace Elektra\SeedBundle\Routing;

use Elektra\CrudBundle\Crud\Navigator;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Loader extends BaseLoader
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

        $collection  = new RouteCollection();
        $definitions = $this->navigator->getDefinitions();

        foreach ($definitions as $definition) {
            if (!$definition->hasRoute()) {
                continue;
            }

            $singular   = $definition->getRouteSingular();
            $plural     = $definition->getRoutePlural();
            $controller = $definition->getController();

            if ($definition->isRoot()) {
                // root definitions have browse / add / view / edit / delete actions without any prefixes
                $this->generateBrowseRoute($collection, $plural, $controller);
                $this->generateAddRoute($collection, $singular, $controller);
                $this->generateRoute($collection, $singular, $controller, 'view');
                $this->generateRoute($collection, $singular, $controller, 'edit');
                $this->generateRoute($collection, $singular, $controller, 'delete');
            }

            if ($definition->hasParents()) {
                foreach ($definition->getParents() as $parentKey) {
                    $paths = array();
                    $this->getPathsForParent($parentKey, $paths);
                    foreach ($paths as $parentPath) {
                        $path = $parentPath . '/' . $singular;
                        $this->generateAddRoute($collection, $path, $controller);
                        $this->generateRoute($collection, $path, $controller, 'view');
                        $this->generateRoute($collection, $path, $controller, 'edit');
                        $this->generateRoute($collection, $path, $controller, 'delete');
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * @param string $parentKey
     * @param array  $paths
     */
    protected function getPathsForParent($parentKey, array &$paths)
    {

        $parentDefinition = $this->navigator->getDefinition($parentKey);
        $singlePart       = $parentDefinition->getRouteSingular();

        if ($parentDefinition->isRoot()) {
            $paths[] = $singlePart;
        }

        if ($parentDefinition->hasParents()) {
            foreach ($parentDefinition->getParents() as $moreParent) {
                $morePaths = array();
                $this->getPathsForParent($moreParent, $morePaths);

                foreach ($morePaths as $morePathPart) {
                    $paths[] = $morePathPart . '/' . $singlePart;
                }
            }
        }
    }

    /**
     * @param RouteCollection $collection
     * @param string          $path
     * @param string          $controller
     */
    private function generateBrowseRoute(RouteCollection $collection, $path, $controller)
    {

        $route = new Route($path . '/{page}{slash}');
        $route->setDefault('_controller', $controller . ':browse');
        $route->setDefault('page', 1);
        $route->setDefault('slash', '/');
        $route->setRequirement('page', '\d+');
        $route->setRequirement('slash', '[/]{0,1}');

        // can use the path here directly, as browse routes are only valid for root routes
        $collection->add($path, $route);
    }

    /**
     * @param RouteCollection $collection
     * @param string          $path
     * @param string          $controller
     */
    private function generateAddRoute(RouteCollection $collection, $path, $controller)
    {

        $routePath = $path . '/add';
        if (strpos($path, '/') !== false) {
            // this id references the embedding type's ID
            $routePath .= '/{id}';
        }
        $routePath .= '{slash}';

        $route = new Route($routePath);
        $route->setDefault('_controller', $controller . ':add');
        $route->setDefault('slash', '/');
        $route->setRequirement('slash', '[/]{0,1}');
        if (strpos($path, '/') !== false) {
            $route->setRequirement('id', '\d+');
        }

        $collection->add(str_replace('/', '.', $path) . '.add', $route);
    }

    /**
     * @param RouteCollection $collection
     * @param string          $path
     * @param string          $controller
     * @param string          $action
     */
    private function generateRoute(RouteCollection $collection, $path, $controller, $action)
    {

        $routePath = $path . '/' . $action . '/{id}{slash}';

        $route = new Route($routePath);
        $route->setDefault('_controller', $controller . ':' . $action);
        $route->setDefault('slash', '/');
        $route->setRequirement('slash', '[/]{0,1}');
        // This id references this entity's ID
        $route->setRequirement('id', '\d+');

        $collection->add(str_replace('/', '.', $path) . '.' . $action, $route);
    }
}