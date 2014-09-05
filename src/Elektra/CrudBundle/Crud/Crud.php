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

    /**
     * @param EntityInterface $parentEntity
     * @param string          $parentRoute
     * @param string          $relationName
     */
    public function setParent(EntityInterface $parentEntity, $parentRoute, $relationName)
    {

        $this->parentEntity = $parentEntity;
        $this->parentRoute  = $parentRoute;
        $this->relationName = $relationName;

        $this->setParentId($this->parentEntity->getId());
    }

    /**
     * @param int $id
     */
    public function setParentId($id)
    {
        $this->setData('parent', $id);
    }

    /**
     * @return int
     */
    public function getParentId()
    {

        return $this->getData('parent');
    }

    /**
     * @return bool
     */
    public function isEmbedded()
    {

        return $this->hasParent();
    }

    /**
     * @return bool
     */
    public function hasParent()
    {

        if (!empty($this->parentEntity)) {
            return true;
        }

        return false;
    }

    /**
     * @return Definition
     */
    public function getParentDefinition()
    {

        if ($this->getParentEntity() == null) {
            $routeParts = explode('.', $this->linker->getActiveRoute());
            // remove the action
            array_pop($routeParts);
            // next pop is the actual type
            $last = array_pop($routeParts);
//            if ($last == 'note') {
//                $parent     = array_pop($routeParts);
//                $definition = $this->getNavigator()->getDefinition($parent);
//
//                return $definition;
//            } else {
                $parent = array_pop($routeParts);
                $definition = $this->getNavigator()->getDefinition($parent);

                return $definition;
//            }
        }

        return $this->getNavigator()->getDefinition(get_class($this->parentEntity));
    }

    /**
     * @return EntityInterface
     */
    public function getParentEntity()
    {

        return $this->parentEntity;
    }

    /**
     * @return string
     */
    public function getParentRoute()
    {

        return $this->parentRoute;
    }

    /**
     * @return string
     */
    public function getParentRelationName()
    {

        return $this->relationName;
    }
}