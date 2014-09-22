<?php

namespace Elektra\SeedBundle\Form\Events;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class UnitSalesStatusType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'name',
            'abbreviation'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('name', 'text', $this->getFieldOptions('name')->required()->notBlank()->toArray());
        $common->add('abbreviation', 'text', $this->getFieldOptions('abbreviation')->required()->notBlank()->toArray());

        if ($options['crud_action'] != 'add')
        {
            $common->add('internalName', 'hidden', $this->getFieldOptions('internalName')->toArray());
        }
    }
}