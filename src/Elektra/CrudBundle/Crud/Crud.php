<?php

namespace Elektra\CrudBundle\Crud;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Definition\Definition;
use Elektra\SeedBundle\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Crud
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
     * @param Controller $controller
     * @param Definition $definition
     */
    public function __construct(Controller $controller, Definition $definition)
    {

        $this->controller = $controller;
        $this->definition = $definition;
    }

    /**
     * @return Controller
     */
    public function getController()
    {

        return $this->controller;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {

        return $this->controller->get('service_container');
    }

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
     * @return Request
     */
    public function getRequest()
    {

        return $this->getContainer()->get('request');
    }

    /**
     * @param string|null $vendor
     * @param string|null $bundle
     * @param string|null $group
     * @param string|null $name
     *
     * @return Definition
     */
    public function getDefinition($vendor = null, $bundle = null, $group = null, $name = null)
    {

        if ($vendor != null && $bundle != null && $group != null && $name != null) {
            return $this->getService('navigator')->getDefinition($vendor, $bundle, $group, $name);
        }

        return $this->definition;
    }

    public function getView($type) {

        $template = $this->getService('templating');
        $prefix = $this->getDefinition()->getPrefixView();
        $common = 'ElektraSeedBundle::base-'.$type.'.html.twig';
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
    public function getActiveBrowseLink()
    {

        $navigator = $this->controller->get('navigator');
        $link      = $navigator->getLink(
            $this->getDefinition(),
            'browse',
            array('page' => $this->get('page', 'browse'))
        );

        return $link;
    }

    /**
     * @return string
     */
    public function getAddLink()
    {

        $navigator = $this->controller->get('navigator');
        $link      = $navigator->getLink(
            $this->getDefinition(),
            'add'
        );

        return $link;
    }

    /**
     * @param EntityInterface|int $entity
     *
     * @return string
     */
    public function getViewLink($entity)
    {

        if ($entity instanceof EntityInterface) {
            $id = $entity->getId();
        } else {
            $id = $entity;
        }

        $navigator = $this->controller->get('navigator');
        $link      = $navigator->getLink(
            $this->getDefinition(),
            'view',
            array('id' => $id)
        );

        return $link;
    }

    /**
     * @param EntityInterface|int $entity
     *
     * @return string
     */
    public function getEditLink($entity)
    {

        if ($entity instanceof EntityInterface) {
            $id = $entity->getId();
        } else {
            $id = $entity;
        }

        $navigator = $this->controller->get('navigator');
        $link      = $navigator->getLink(
            $this->getDefinition(),
            'edit',
            array('id' => $id)
        );

        return $link;
    }

    /**
     * @param EntityInterface|int $entity
     *
     * @return string
     */
    public function getDeleteLink($entity)
    {

        if ($entity instanceof EntityInterface) {
            $id = $entity->getId();
        } else {
            $id = $entity;
        }

        $navigator = $this->controller->get('navigator');
        $link      = $navigator->getLink(
            $this->getDefinition(),
            'delete',
            array('id' => $id)
        );

        return $link;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param string $action
     */
    public function save($name, $value, $action = '')
    {

        $key     = $this->getStorageKey($name, $action);
//        echo 'setting key: '.$key.'<br />';
        $session = $this->controller->get('session');

        $session->set($key, $value);
    }

    /**
     * @param string     $name
     * @param string     $action
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($name, $action = '', $default = null)
    {

        $key     = $this->getStorageKey($name, $action);
//        echo 'getting key: '.$key.'<br />';
        $session = $this->controller->get('session');

        return $session->get($key, $default);
    }

    /**
     * @param string $name
     * @param string $action
     *
     * @return string
     */
    private function getStorageKey($name, $action)
    {

        $key = $this->getDefinition()->getKey();
        $key .= '_' . $name;
        if ($action != '') {
            $key .= '.' . $action;
        }

        return $key;
    }

    /**
     * @return string
     */
    public function getLangKey()
    {

        return $this->getDefinition()->getGroupLang() . '.' . $this->getDefinition()->getNameLang();
    }
}