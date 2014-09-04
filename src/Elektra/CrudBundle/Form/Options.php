<?php

namespace Elektra\CrudBundle\Form;

use Elektra\SiteBundle\Site\Helper;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Traversable;

class Options implements \ArrayAccess, \IteratorAggregate
{

    /**
     * @var Form
     */
    protected $crudForm;

    protected $fieldName;

    protected $fieldLangKey;

    protected $data;

    public function __construct(Form $crudForm, $fieldName)
    {

        $this->crudForm     = $crudForm;
        $this->fieldName    = $fieldName;
        $this->fieldLangKey = Helper::camelToUnderScore($fieldName);
        $this->data         = array();
    }

    public function getIterator()
    {

        return new \ArrayIterator($this->data);
    }

    public function offsetExists($offset)
    {

        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {

        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }

        return null;
    }

    public function offsetSet($offset, $value)
    {

        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {

        unset($this->data[$offset]);
    }

    public function required()
    {

        $this->data['required'] = true;

        return $this;
    }

    public function optional()
    {

        $this->data['required'] = false;

        return $this;
    }

    public function notBlank()
    {

        $language = $this->crudForm->getCrud()->getService('siteLanguage');
        $langKey  = $this->crudForm->getCrud()->getLanguageKey();

        $key       = 'forms.' . $langKey . '.constraints.' . $this->fieldLangKey . '.not_blank';
        $alternate = 'forms.generic.constraints.not_blank';

        $this->data['constraints'][] = new NotBlank(array('message' => $language->getAlternate($key, $alternate)));

        return $this;
    }

    public function uniqueEntity($name = 'name')
    {

        $language = $this->crudForm->getCrud()->getService('siteLanguage');
        $langKey  = $this->crudForm->getCrud()->getLanguageKey();

        $key       = 'forms.' . $langKey . '.constraints.' . $this->fieldLangKey . '.unique_entity.' . $name;
        $alternate = 'forms.generic.constraints.not_blank';

        $this->data['constraints'][] = new UniqueEntity(array(
            'fields'  => $name,
            'message' => $language->getAlternate($key, $alternate),
        ));

        return $this;
    }

    public function uniqueName()
    {

        return $this->uniqueEntity('name');
        //        return $this;
    }

    public function add($name, $value)
    {

        $this->data[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {

        return iterator_to_array($this->getIterator());
    }
}