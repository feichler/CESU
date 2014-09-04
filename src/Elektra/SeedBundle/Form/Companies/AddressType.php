<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\CommonOptions;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        // No groups as this form type may only embedded within a physical location

        $builder->add('street1', 'text', $this->getFieldOptions('street1')->required()->notBlank()->toArray());
        $builder->add('street2', 'text', $this->getFieldOptions('street2')->optional()->toArray());
        $builder->add('street3', 'text', $this->getFieldOptions('street3')->optional()->toArray());
        $builder->add('postalCode', 'text', $this->getFieldOptions('postalCode')->required()->notBlank()->toArray());
        $builder->add('city', 'text', $this->getFieldOptions('city')->required()->notBlank()->toArray());
        $builder->add('state', 'text', $this->getFieldOptions('state')->optional()->toArray());
        $countryFieldOptions = $this->getFieldOptions('country')->required()->notBlank();
        $countryFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Country')->getClassEntity());
        $countryFieldOptions->add('property', 'title');
        $builder->add('country', 'entity', $countryFieldOptions->toArray());
    }
}