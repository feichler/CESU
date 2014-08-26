<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $builder->add('locationIdentifier', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('shortName', 'text', CommonOptions::getRequiredNotBlank());

        $addressOptions = array(
            'data_class' =>   $this->getCrud()->getDefinition('Elektra','Seed','Companies','Address')->getClassEntity(),
            'crud_action' => $options['crud_action'],
            'show_buttons' => false
        );
        $builder->add("address", new AddressType($this->getCrud()), $addressOptions);
    }
}