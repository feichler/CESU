<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class CountryType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'name',
            'alphaTwo',
            'alphaThree',
            'numericCode',
        );
    }
    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {
        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('name', 'text', $this->getFieldOptions('name')
            ->required()
            ->notBlank()
            ->toArray());

        $common->add('region','entity', $this->getFieldOptions('region')
            ->required()
            ->notBlank()
            ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region')->getClassEntity())
            ->add('property' , 'name')
            ->toArray());

        $common->add('alphaTwo', 'text', $this->getFieldOptions('alphaTwo')
            ->required()
            ->notBlank()
            ->toArray());

        $common->add('alphaThree', 'text', $this->getFieldOptions('alphaThree')
            ->required()
            ->notBlank()
            ->toArray());

        $common->add('numericCode', 'text', $this->getFieldOptions('numericCode')
            ->required()
            ->notBlank()
            ->toArray());
    }
}