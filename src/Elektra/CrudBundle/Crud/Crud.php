<?php

namespace Elektra\CrudBundle\Crud;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

final class Crud
{

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var Definition
     */
    protected $definition;

    /**
     * @var Linker
     */
    protected $linker;

    /**
     * @param Controller $controller
     * @param Definition $definition
     */
    public function __construct(Controller $controller, Definition $definition)
    {

        $this->controller = $controller;
        $this->definition = $definition;

        $this->linker = new Linker($this);
    }

    /*************************************************************************
     * Common Getters and their shortcuts
     *************************************************************************/

    /**
     * @param string $id
     * @param int    $invalidBehavior
     *
     * @return object
     */
    public function getService($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {

        return $this->getContainer()->get($id, $invalidBehavior);
    }

    /**
     * @return Controller
     */
    public function getController()
    {

        return $this->controller;
    }

    /**
     * @param mixed|null  $vendor
     * @param string|null $bundle
     * @param string|null $group
     * @param string|null $name
     *
     * @return Definition
     */
    public function getDefinition($vendor = null, $bundle = null, $group = null, $name = null)
    {

        if ($vendor != null) {
            // check only first value, because navigator also requires only one to try finding a definition
            $navigator = $this->getNavigator();

            return $navigator->getDefinition($vendor, $bundle, $group, $name);
        } else {
            // no values given -> return the stored definition of THIS crud
            return $this->definition;
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {

        // no call to getService -> would loop
        return $this->getController()->get('service_container');
    }

    /**
     * @return Navigator
     */
    public function getNavigator()
    {

        return $this->getService('navigator');
    }

    /**
     * @return Request
     */
    public function getRequest()
    {

        return $this->getService('request');
    }

    /**
     * @return Linker
     */
    public function getLinker()
    {

        return $this->linker;
    }

    /*************************************************************************
     * Data Store
     *************************************************************************/

    /**
     * @param string $name
     * @param mixed  $value
     * @param string $action
     */
    public function setData($name, $value, $action = '')
    {

        $key     = $this->getDataKey($name, $action);
        $session = $this->getService('session');

        $session->set($key, $value);
    }

    /**
     * @param string $name
     * @param string $action
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getData($name, $action = '', $default = null)
    {

        $key     = $this->getDataKey($name, $action);
        $session = $this->getService('session');

        return $session->get($key, $default);
    }

    /**
     * @param string $name
     * @param string $action
     *
     * @return string
     */
    private function getDataKey($name, $action)
    {

        $key = $this->getDefinition()->getKey();
        $key .= '__' . $name;
        if (!empty($action)) {
            $key .= '__' . $action;
        }

        return $key;
    }

    /*************************************************************************
     * View / Template & Language Related
     *************************************************************************/

    /**
     * @param string $type
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getView($type)
    {

        $template = $this->getService('templating');
        $prefix   = $this->getDefinition()->getView();
        $common   = 'ElektraSeedBundle::base-' . $type . '.html.twig';
        $specific = $prefix . ':' . $type . '.html.twig';

        if ($template->exists($specific)) {
            return $specific;
        } else {
            if ($template->exists($common)) {
                return $common;
            }
        }

        throw new \RuntimeException('Fatal Error: View of type "' . $type . '" could not be found');
    }

    /**
     * @return string
     */
    public function getLanguageKey()
    {

        return $this->getDefinition()->getLanguageKey();
    }

    /**
     * @var EntityInterface
     */
    protected $parentEntity;

    /**
     * @var string
     */
    protected $parentRoute;

    /**
     * @var string
     */
    protected $relationName;

    public function setParent(EntityInterface $parentEntity, $parentRoute, $relationName)
    {

        $this->parentEntity = $parentEntity;
        $this->parentRoute  = $parentRoute;
        $this->relationName = $relationName;

        $this->setParentId($this->parentEntity->getId());
        //        $this->setData('parent',);
    }

    public function setParentId($id)
    {

        $this->setData('parent', $id);
    }

    public function getParentId()
    {

        return $this->getData('parent');
    }

    public function isEmbedded()
    {

        return $this->hasParent();
    }

    public function hasParent()
    {

        if (!empty($this->parentEntity)) {
            return true;
        }

        return false;
        //        $route = $this->getLinker()->getActiveRoute();
        //        echo $route . '<br />';
        //        echo $this->getDefinition()->getName();
        //
        //        return false;
    }

    public function getParentDefinition()
    {

        if ($this->parentEntity == null) {
            $routeParts = explode('.', $this->linker->getActiveRoute());
            // remove the action
            array_pop($routeParts);
            // next pop is the actual type
            $last = array_pop($routeParts);
            if ($last == 'note') {
                $parent     = array_pop($routeParts);
                $definition = $this->getNavigator()->getDefinition($parent);

                return $definition;
            }
        }
        //
        //        $last = array_pop($routeParts);
        //        $last2 = array_pop($routeParts);
        //        echo $last.'<br />';
        //        echo $last2.'<br />';
        //        if($last2 == 'note') {
        //            echo get_class($definition);
        //        }
        //
        //        var_dump($this->linker->getActiveRoute());
        //        var_dump($this->parentRoute);
        //        var_dump($this->parentEntity);
        //echo get_class($this->parentEntity);
        return $this->getNavigator()->getDefinition(get_class($this->parentEntity));
        //        echo get_class($this->parentEntity);
    }

    public function getParentEntity()
    {

        return $this->parentEntity;
    }

    public function getParentRoute()
    {

        return $this->parentRoute;
    }

    public function getParentRelationName()
    {

        return $this->relationName;
    }

    /*************************************************************************
     * Link methods
     *************************************************************************/

    //    public function getAfterProcessReturnUrl1()
    //    {
    //
    //        $routeParts = explode('.', $this->getRouteName());
    //        // pop the action off
    //        $action = array_pop($routeParts);
    //
    //        if (count($routeParts) == 1) {
    //            // root route -> return to browsing
    //            $rootDefinition = $this->getNavigator()->getDefinition($routeParts[0]);
    //            //            $browseRouteName = $rootDefinition->getRoutePlural();
    //            $page = $this->getData('page', 'browse');
    //            $url  = $this->getNavigator()->getBrowseLink($rootDefinition, array('page' => $page));
    //
    //            //            echo $url;
    //
    //            return $url;
    //        } else {
    //            // embedded -> return to parent view
    //            $last = end($routeParts);
    //            reset($routeParts);
    //            $routeName = implode('.', $routeParts);
    //            $routeName .= '.view';
    //            // URGENT how to get the parent id?!
    //            // URGENT implement this method
    //        }
    //    }
    //
    //    public function getCloseLink1($entry)
    //    {
    //
    //        $routeParts = explode('.', $this->getRouteName());
    //        $action     = array_pop($routeParts);
    //
    //        if (count($routeParts) == 1) {
    //            $rootDefinition = $this->getNavigator()->getDefinition($routeParts[0]);
    //            $page           = $this->getData('page', 'browse');
    //            $url            = $this->getNavigator()->getBrowseLink($rootDefinition, array('page' => $page));
    //
    //            return $url;
    //        } else {
    //            // URGENT implement
    //        }
    //    }
    //
    //    public function getEditLink1($entry)
    //    {
    //
    //        $routeParts = explode('.', $this->getRouteName());
    //        $action     = array_pop($routeParts);
    //
    //        if (count($routeParts) == 1) {
    //            $rootDefinition = $this->getNavigator()->getDefinition($routeParts[0]);
    //            $url            = $this->getNavigator()->getLink($rootDefinition, 'edit', array('id' => $entry->getId()));
    //
    //            return $url;
    //        } else {
    //            // URGENT implement
    //        }
    //    }
    //
    //    public function getDeleteLink1($entry)
    //    {
    //
    //        $routeParts = explode('.', $this->getRouteName());
    //        $action     = array_pop($routeParts);
    //
    //        if (count($routeParts) == 1) {
    //            $rootDefinition = $this->getNavigator()->getDefinition($routeParts[0]);
    //            $url            = $this->getNavigator()->getLink($rootDefinition, 'delete', array('id' => $entry->getId()));
    //
    //            return $url;
    //        } else {
    //            // URGENT implement
    //        }
    //    }
    //
    //    public function getLink1($action, $entry = null)
    //    {
    //
    //        if ($action == 'browse') {
    //            $parameters = array('page' => $this->getData('page', 'browse', 1));
    //
    //            return $this->getNavigator()->getBrowseLink($this->getDefinition(), $parameters);
    //        }
    //
    //        $routeParts = explode('.', $this->getRouteName());
    //        $action     = array_pop($routeParts);
    //
    //        if (count($routeParts) == 1) {
    //            // root route
    //            $rootDefinition = $this->getNavigator()->getDefinition($routeParts[0]);
    //            $routeName      = $rootDefinition->getRouteSingular() . '.' . $action;
    //            $link           = $this->getNavigator()->getLinkFromRoute($routeName, array('id' => $entry->getId()));
    //
    //            return $link;
    //        } else {
    //        }
    //
    //        var_dump($routeParts);
    //        $routeName = $this->getRouteName();
    //        if (strpos($routeName, '.') !== false) {
    //            // URGENT prepare the route name
    //        } else {
    //            $linkRouteName = $this->getDefinition()->getRouteSingular() . '.' . $action;
    //        }
    //
    //        $parameters = array();
    //        if ($entry !== null && $entry instanceof EntityInterface) {
    //            $parameters['id'] = $entry->getId();
    //        }
    //        echo $routeName . '<br />';
    //
    //        return $this->getNavigator()->getLinkFromRoute($linkRouteName, $parameters);
    //
    //        //        echo $addRouteName.'<br />';
    //
    //        //        echo $this->getRouteName();
    //        //        echo 'ASDF';
    //    }

    /*************************************************************************
     * Generic Helper methods
     *************************************************************************/

    /**
     * @param array $options1
     * @param array $options2
     *
     * @return array
     */
    public function mergeOptions(array $options1, array $options2)
    {

        $merged = $options1;

        foreach ($options2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->mergeOptions($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}