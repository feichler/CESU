<?php

namespace Elektra\CrudBundle\Crud;

abstract class Definition
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $parents;

    /**
     * @var bool
     */
    protected $root;

    public function __construct($vendor, $bundle, $group, $name)
    {

        /*
         * ensure the <XXX>Bundle style
         */
        $bundle = (strpos($bundle, 'Bundle') === false ? $bundle . 'Bundle' : $bundle);

        /*
         * generate the unique key
         */
        $this->key = static::generateKey($vendor, $bundle, $group, $name);

        /*
         * initialise the variables
         */
        $this->data    = array();
        $this->parents = array();
        $this->root    = false;

        /*
         * set the plain data
         */
        $this->setData('vendor', $vendor);
        $this->setData('bundle', $bundle);
        $this->setData('group', $group);
        $this->setData('name', $name);

        /*
         * set some common composite data bits
         */
        // language key used for all translations
        $this->setData('languageKey', strtolower($this->getData('group')) . '.' . strtolower($this->getData('name')));
        // full bundle name - symfony style
        $this->setData('bundleFull', $this->getData('vendor') . $this->getData('bundle'));
        // namespace of the bundle
        $this->setData('bundleNamespace', $this->getData('vendor') . '\\' . $this->getData('bundle'));
        // controller name (class) - symfony style
        $this->setData('controller', $this->getData('bundleFull') . ':' . $this->getData('group') . '\\' . $this->getData('name'));
        // view name / template prefix - symfony style - same as controller
        $this->setData('view', $this->getData('bundleFull') . ':' . $this->getData('group') . '\\' . $this->getData('name'));
        /*
         * set the classes
         */
        // Entity - full qualified
        $this->setData('class.entity', $this->getData('bundleNamespace') . '\\Entity\\' . $this->getData('group') . '\\' . $this->getData('name'));
        // Repository - symfony style
        $this->setData('class.repository', $this->getData('bundleFull') . ':' . $this->getData('group') . '\\' . $this->getData('name'));
        // Form - full qualified
        $this->setData('class.form', $this->getData('bundleNamespace') . '\\Form\\' . $this->getData('group') . '\\' . $this->getData('name') . 'Type');
        // Table - full qualified
        $this->setData('class.table', $this->getData('bundleNamespace') . '\\Table\\' . $this->getData('group') . '\\' . $this->getData('name') . 'Table');

        /*
         * set some properties of the entity
         */
        $interfaces = class_implements($this->getData('class.entity'));
        if (array_key_exists('Elektra\SeedBundle\Entity\AuditableInterface', $interfaces)) {
            $this->setData('property.auditable', true);
        }
        if (array_key_exists('Elektra\SeedBundle\Entity\AnnotableInterface', $interfaces)) {
            $this->setData('property.annotable', true);
        }
    }

    /*************************************************************************
     * Direct Getters / Setters
     *************************************************************************/

    /**
     * @return string
     */
    public function getKey()
    {

        return $this->key;
    }

    /**
     * @param string $type
     * @param mixed  $value
     */
    protected function setData($type, $value)
    {

        $this->data[$type] = $value;
    }

    /***
     * @param string $type
     *
     * @return bool
     */
    public function hasData($type)
    {

        if (array_key_exists($type, $this->data)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $type
     *
     * @return mixed|null
     */
    public function getData($type)
    {

        if ($this->hasData($type)) {
            return $this->data[$type];
        }

        return null;
    }

    /**
     *
     */
    protected function setRoot()
    {

        $this->root = true;
    }

    /**
     * @return bool
     */
    public function isRoot()
    {

        return $this->root;
    }

    /**
     * If only first parameter used it has to be a valid definition key.
     * Otherwise all parameters have to be used and <code>$key</code> is the vendor
     *
     * @param string      $key
     * @param string|null $bundle
     * @param string|null $group
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     */
    public function addParent($key, $bundle = null, $group = null, $name = null)
    {

        if ($bundle !== null && $group !== null && $name !== null) {
            $key = static::generateKey($key, $bundle, $group, $name);
        }

        if (!static::keyValid($key)) {
            throw new \InvalidArgumentException('Key "' . $key . '" is not valid');
        }

        $this->parents[] = $key;
    }

    /**
     * @return bool
     */
    public function hasParents()
    {

        return count($this->parents) != 0;
    }

    /**
     * @return array
     */
    public function getParents()
    {

        return $this->parents;
    }

    /*************************************************************************
     * Shortcut Getters / Setters
     *************************************************************************/

    /**
     * @return string
     */
    public function getName()
    {

        return $this->getData('name');
    }

    /**
     * @param string $route
     */
    protected function setRouteSingular($route)
    {

        $this->setData('route.singular', $route);
    }

    /**
     * @param string $route
     */
    protected function setRoutePlural($route)
    {

        $this->setData('route.plural', $route);
    }

    /**
     * @return bool
     */
    public function hasRoute()
    {

        if ($this->isRoot()) {
            return $this->hasData('route.singular') && $this->hasData('route.plural');
        } else {
            return $this->hasData('route.singular');
        }
    }

    /**
     * @return string|null
     */
    public function getRouteSingular()
    {

        return $this->getData('route.singular');
    }

    /**
     * @return string|null
     */
    public function getRoutePlural()
    {

        return $this->getData('route.plural');
    }

    /**
     * @return null|string
     */
    public function getClassEntity()
    {

        return $this->getData('class.entity');
    }

    /**
     * @return null|string
     */
    public function getClassRepository()
    {

        return $this->getData('class.repository');
    }

    /**
     * @return null|string
     */
    public function getClassForm()
    {

        return $this->getData('class.form');
    }

    /**
     * @return null|string
     */
    public function getClassTable()
    {

        return $this->getData('class.table');
    }

    /**
     * @return bool
     */
    public function isEntityAuditable()
    {

        $flag = $this->getData('property.auditable');
        if ($flag === null) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isEntityAnnotable()
    {

        $flag = $this->getData('property.annotable');
        if ($flag === null) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getController()
    {

        return $this->getData('controller');
    }

    /**
     * @return string
     */
    public function getView()
    {

        return $this->getData('view');
    }

    /**
     * @return string
     */
    public function getLanguageKey()
    {

        return $this->getData('languageKey');
    }

    /*************************************************************************
     * Static key-related helpers
     *************************************************************************/

    /**
     * @param string $vendor
     * @param string $bundle
     * @param string $group
     * @param string $name
     *
     * @return string
     */
    public static function generateKey($vendor, $bundle, $group, $name)
    {

        return $vendor . '-' . (strpos($bundle, 'Bundle') === false ? $bundle . 'Bundle' : $bundle) . '-' . $group . '-' . $name;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function keyValid($key)
    {

        $keyArray = explode('-', $key);
        if (count($keyArray) !== 4) {
            return false;
        }

        $vendor = $keyArray[0];
        $bundle = $keyArray[1];
        $group  = $keyArray[2];
        $name   = $keyArray[3];

        // Key is valid if an entity class for the combination exists
        $class = $vendor . '\\' . $bundle . '\\Entity\\' . $group . '\\' . $name;
        if (!class_exists($class)) {
            return false;
        }

        return true;
    }

    public function getTest(){
        return '';
    }
}