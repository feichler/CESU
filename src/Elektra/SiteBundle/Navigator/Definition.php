<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Navigator;

/**
 * Class Definition
 *
 * @package Elektra\SiteBundle\Navigator
 *
 * @version 0.1-dev
 */
class Definition
{

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $completeBundle;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $group;

    /**
     * NOTE/TRANSLATE: used for translations -> replaces CRUDControllerOptions $languages['type']
     *
     * @var string
     */
    protected $type;

    /**
     * NOTE/TRANSLATE: used for translations -> replaces CRUDControllerOptions $languages['section']
     *
     * @var string
     */
    protected $typeGroup;

    /**
     * @var string
     */
    protected $namespacePrefix;

    /**
     * @var string
     */
    protected $routeNamePrefix;

    /**
     * @var string
     */
    protected $viewPrefix;

    /**
     * @var string
     */
    protected $classEntity;

    /**
     * @var string
     */
    protected $classRepository;

    /**
     * @var string
     */
    protected $classForm;

    /**
     * @var string
     */
    protected $classTable;

    /**
     * @var string
     */
    protected $controllerName;

    /**
     * @var string
     */
    protected $routePath;

    /**
     * @param string $vendor
     * @param string $bundle
     * @param string $group
     * @param string $name
     */
    public function __construct($vendor, $bundle, $group, $name)
    {

        $this->vendor = $vendor;
        $this->bundle = $bundle;
        $this->name   = $name;
        $this->group  = $group;

        $this->prepare();
    }

    /**
     *
     */
    private function prepare()
    {

        $this->completeBundle = $this->vendor . $this->bundle;

        $this->namespacePrefix = $this->vendor . '\\' . $this->bundle . '\\';
        $this->type            = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->name));
        $this->typeGroup       = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->group));
        $this->routeNamePrefix = $this->completeBundle . '_' . $this->group . '_' . $this->name;
        $this->viewPrefix      = $this->completeBundle . ':' . $this->group . '/' . $this->name;
        $this->classEntity     = $this->namespacePrefix . 'Entity\\' . $this->group . '\\' . $this->name;
        $this->classRepository = $this->completeBundle . ':' . $this->group . '\\' . $this->name;
        $this->classForm       = $this->namespacePrefix . 'Form\\' . $this->group . '\\' . $this->name . 'Type';
        $this->classTable      = $this->namespacePrefix . 'Table\\' . $this->group . '\\' . $this->name . 'Table';
        $this->controllerName  = $this->completeBundle . ':' . $this->group . '/' . $this->name;
        $this->routePath       = $this->group . '/' . $this->name;
    }

    /**
     * @param string $bundle
     */
    public function setBundle($bundle)
    {

        $this->bundle = $bundle;
    }

    /**
     * @return string
     */
    public function getBundle()
    {

        return $this->bundle;
    }

    /**
     * @param string $classEntity
     */
    public function setClassEntity($classEntity)
    {

        $this->classEntity = $classEntity;
    }

    /**
     * @return string
     */
    public function getClassEntity()
    {

        return $this->classEntity;
    }

    /**
     * @param string $classForm
     */
    public function setClassForm($classForm)
    {

        $this->classForm = $classForm;
    }

    /**
     * @return string
     */
    public function getClassForm()
    {

        return $this->classForm;
    }

    /**
     * @param string $classRepository
     */
    public function setClassRepository($classRepository)
    {

        $this->classRepository = $classRepository;
    }

    /**
     * @return string
     */
    public function getClassRepository()
    {

        return $this->classRepository;
    }

    /**
     * @param string $classTable
     */
    public function setClassTable($classTable)
    {

        $this->classTable = $classTable;
    }

    /**
     * @return string
     */
    public function getClassTable()
    {

        return $this->classTable;
    }

    /**
     * @param string $completeBundle
     */
    public function setCompleteBundle($completeBundle)
    {

        $this->completeBundle = $completeBundle;
    }

    /**
     * @return string
     */
    public function getCompleteBundle()
    {

        return $this->completeBundle;
    }

    /**
     * @param string $controllerName
     */
    public function setControllerName($controllerName)
    {

        $this->controllerName = $controllerName;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {

        return $this->controllerName;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {

        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getGroup()
    {

        return $this->group;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * @param string $namespacePrefix
     */
    public function setNamespacePrefix($namespacePrefix)
    {

        $this->namespacePrefix = $namespacePrefix;
    }

    /**
     * @return string
     */
    public function getNamespacePrefix()
    {

        return $this->namespacePrefix;
    }

    /**
     * @param string $routeNamePrefix
     */
    public function setRouteNamePrefix($routeNamePrefix)
    {

        $this->routeNamePrefix = $routeNamePrefix;
    }

    /**
     * @return string
     */
    public function getRouteNamePrefix()
    {

        return $this->routeNamePrefix;
    }

    /**
     * @param string $routePath
     */
    public function setRoutePath($routePath)
    {

        $this->routePath = $routePath;
    }

    /**
     * @return string
     */
    public function getRoutePath()
    {

        return $this->routePath;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {

        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * @param string $typeGroup
     */
    public function setTypeGroup($typeGroup)
    {

        $this->typeGroup = $typeGroup;
    }

    /**
     * @return string
     */
    public function getTypeGroup()
    {

        return $this->typeGroup;
    }

    /**
     * @param string $vendor
     */
    public function setVendor($vendor)
    {

        $this->vendor = $vendor;
    }

    /**
     * @return string
     */
    public function getVendor()
    {

        return $this->vendor;
    }

    /**
     * @param string $viewPrefix
     */
    public function setViewPrefix($viewPrefix)
    {

        $this->viewPrefix = $viewPrefix;
    }

    /**
     * @return string
     */
    public function getViewPrefix()
    {

        return $this->viewPrefix;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {

        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {

        return $this->key;
    }
}