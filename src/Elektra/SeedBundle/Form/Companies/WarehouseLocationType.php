<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WarehouseLocationType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function setSpecificDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('locationIdentifier', 'text', $this->getFieldOptions('locationIdentifier')->required()->notBlank()->toArray());
        $common->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $addressFieldOptions = $this->getFieldOptions('address', false);
        $addressFieldOptions->add('data_class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address')->getClassEntity());
        $addressFieldOptions->add('crud_action', $options['crud_action']);
        $addressFieldOptions->add('default_actions', false);
        $common->add('address', new AddressType($this->getCrud()), $addressFieldOptions->toArray());
    }
}