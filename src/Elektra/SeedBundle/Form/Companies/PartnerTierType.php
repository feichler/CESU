<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class PartnerTierType extends CrudForm
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
        $common->add('unitsLimit', 'integer', $this->getFieldOptions('unitsLimit')->required()->notBlank()->toArray());
    }
}