<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class ModelType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'name',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('name', 'text', $this->getFieldOptions('name')->required()->notBlank()->toArray());
        $common->add('description', 'textarea', $this->getFieldOptions('description')->optional()->toArray());
    }
}