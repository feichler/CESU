<?php

namespace Elektra\CrudBundle\Definition;

// CHECK make abstract?

class Definition
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var array
     */
    protected $prefixes;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var array
     */
    protected $flags;

    public function __construct($vendor, $bundle, $group, $name)
    {

        $this->classes  = array(
            'entity'     => '', // Fully qualified PHP Class Name for Entities
            'repository' => '', // Symfony Class Name for Repositories
            'form'       => '', // Fully qualified PHP Class Name for Forms
            'table'      => '', // Fully qualified PHP Class Name for Tables
        );
        $this->prefixes = array(
            'route'     => '', // Prefix for the route name (self-defined)
            'view'      => '', // Prefix for the view (Symfony style)
            'namespace' => '', // Namespace Prefix for the classes (Vendor + Bundle)
        );
        $this->values   = array(
            'vendor'     => '', // plain value
            'bundle'     => '', // plain value
            'name'       => '', // plain value
            'group'      => '', // plain value
            'bundleName' => '', // plain value
            'controller' => '', // Symfony style Controller Name (for routing)
            'path'       => '', // URL Path for routing
            'nameLang'   => '', // translation string
            'groupLang'  => '', // translation string
        );
        $this->filters  = array();
        $this->flags    = array(
            'auditable' => false,
            'annotable' => false,
        );

        $this->setVendor($vendor);
        $this->setBundle($bundle);
        $this->setName($name);
        $this->setGroup($group);

        $this->setBundleName($this->getVendor() . $this->getBundle());
        $this->setController($this->getBundleName() . ':' . $this->getGroup() . '/' . $this->getName());
        $this->setPath($this->getGroup() . '/' . $this->getName());
        $this->setNameLang(strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->getName())));
        $this->setGroupLang(strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $this->getGroup())));

        $this->setPrefixRoute($this->getBundleName() . '_' . $this->getGroup() . '_' . $this->getName());
        $this->setPrefixView($this->getBundleName() . ':' . $this->getGroup() . '/' . $this->getName());
        $this->setPrefixNamespace($this->getVendor() . '\\' . $this->getBundle() . '\\');

        $this->setClassEntity($this->getPrefixNamespace() . 'Entity\\' . $this->getGroup() . '\\' . $this->getName());
        $this->setClassRepository($this->getBundleName() . ':' . $this->getGroup() . '\\' . $this->getName());
        $this->setClassForm($this->getPrefixNamespace() . 'Form\\' . $this->getGroup() . '\\' . $this->getName() . 'Type');
        $this->setClassTable($this->getPrefixNamespace() . 'Table\\' . $this->getGroup() . '\\' . $this->getName() . 'Table');

        $interfaces = class_implements($this->getClassEntity());
        if (array_key_exists('Elektra\SeedBundle\Entity\AuditableInterface', $interfaces)) {
            $this->setFlag('auditable', true);
        }
        if (array_key_exists('Elektra\SeedBundle\Entity\AnnotableInterface', $interfaces)) {
            $this->setFlag('annotable', true);
        }
        // CHECK also ask for CRUDEntityInterface and throw exception if not present?
    }

    /*************************************************************************
     * Identifier methods
     *************************************************************************/

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

    /*************************************************************************
     * Universal methods
     *************************************************************************/

    /**
     * @param string $type
     * @param string $class
     */
    public function setClass($type, $class)
    {

        $this->classes[$type] = $class;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasClass($type)
    {

        if (array_key_exists($type, $this->classes)) {
            return true;
        }
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getClass($type)
    {

        if ($this->hasClass($type)) {
            return $this->classes[$type];
        }

        return null;
    }

    /**
     * @param string $type
     * @param string $prefix
     */
    public function setPrefix($type, $prefix)
    {

        $this->prefixes[$type] = $prefix;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasPrefix($type)
    {

        if (array_key_exists($type, $this->prefixes)) {
            return true;
        }
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getPrefix($type)
    {

        if ($this->hasPrefix($type)) {
            return $this->prefixes[$type];
        }

        return null;
    }

    /**
     * @param string $type
     * @param string $value
     */
    public function setValue($type, $value)
    {

        $this->values[$type] = $value;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasValue($type)
    {

        if (array_key_exists($type, $this->values)) {
            return true;
        }
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getValue($type)
    {

        if ($this->hasValue($type)) {
            return $this->values[$type];
        }

        return null;
    }

    /**
     * @param string $type
     * @param bool   $flag
     */
    public function setFlag($type, $flag)
    {

        $this->flags[$type] = $flag;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasFlag($type)
    {

        if (array_key_exists($type, $this->flags)) {
            return true;
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function getFlag($type)
    {

        if ($this->hasFlag($type)) {
            return $this->flags[$type];
        }

        return null;
    }

    /*************************************************************************
     * Specific  methods
     *************************************************************************/

    /**
     * @param string $class
     */
    public function setClassEntity($class)
    {

        $this->setClass('entity', $class);
    }

    /**
     * @return string
     */
    public function getClassEntity()
    {

        return $this->getClass('entity');
    }

    /**
     * @param string $class
     */
    public function setClassForm($class)
    {

        $this->setClass('form', $class);
    }

    /**
     * @return string
     */
    public function getClassForm()
    {

        return $this->getClass('form');
    }

    /**
     * @param string $class
     */
    public function setClassRepository($class)
    {

        $this->setClass('repository', $class);
    }

    /**
     * @return string
     */
    public function getClassRepository()
    {

        return $this->getClass('repository');
    }

    /**
     * @param string $class
     */
    public function setClassTable($class)
    {

        $this->setClass('table', $class);
    }

    /**
     * @return string
     */
    public function getClassTable()
    {

        return $this->getClass('table');
    }

    /**
     * @param string $prefix
     */
    public function setPrefixRoute($prefix)
    {

        $this->setPrefix('route', $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixRoute()
    {

        return $this->getPrefix('route');
    }

    /**
     * @param string $prefix
     */
    public function setPrefixView($prefix)
    {

        $this->setPrefix('view', $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixView()
    {

        return $this->getPrefix('view');
    }

    /**
     * @param string $prefix
     */
    public function setPrefixNamespace($prefix)
    {

        $this->setPrefix('namespace', $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixNamespace()
    {

        return $this->getPrefix('namespace');
    }

    /**
     * @param string $vendor
     */
    public function setVendor($vendor)
    {

        $this->setValue('vendor', $vendor);
    }

    /**
     * @return string
     */
    public function getVendor()
    {

        return $this->getValue('vendor');
    }

    /**
     * @param string $bundle
     */
    public function setBundle($bundle)
    {

        $this->setValue('bundle', (strpos($bundle, 'Bundle') === false ? $bundle . 'Bundle' : $bundle));
    }

    /**
     * @return string
     */
    public function getBundle()
    {

        return $this->getValue('bundle');
    }

    /**
     * @param string $bundleName
     */
    public function setBundleName($bundleName)
    {

        $this->setValue('bundleName', $bundleName);
    }

    /**
     * @return string
     */
    public function getBundleName()
    {

        return $this->getValue('bundleName');
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->setValue('name', $name);
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->getValue('name');
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {

        $this->setValue('group', $group);
    }

    /**
     * @return string
     */
    public function getGroup()
    {

        return $this->getValue('group');
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {

        $this->setValue('controller', $controller);
    }

    /**
     * @return string
     */
    public function getController()
    {

        return $this->getValue('controller');
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {

        $this->setValue('path', $path);
    }

    /**
     * @return string
     */
    public function getPath()
    {

        return $this->getValue('path');
    }

    /**
     * @param string $nameLang
     */
    public function setNameLang($nameLang)
    {

        $this->setValue('nameLang', $nameLang);
    }

    /**
     * @return string
     */
    public function getNameLang()
    {

        return $this->getValue('nameLang');
    }

    /**
     * @param string $groupLang
     */
    public function setGroupLang($groupLang)
    {

        $this->setValue('groupLang', $groupLang);
    }

    /**
     * @return string
     */
    public function getGroupLang()
    {

        return $this->getValue('groupLang');
    }

    /**
     * @param string $action
     *
     * @return string
     */
    public function getRoute($action)
    {

        $route = $this->getPrefixRoute() . '_' . $action;

        return $route;
    }

    /**
     * @return bool
     */
    public function isAuditable()
    {

        return $this->getFlag('auditable');
    }

    /**
     * @return bool
     */
    public function isAnnotable()
    {

        return $this->getFlag('annotable');
    }
}