<?php

namespace Elektra\SeedBundle\Form\Imports;

use Elektra\CrudBundle\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeedUnitType extends Form
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

        $common = $this->addFieldGroup($builder,$options,'common');

        $common->add('file','file',$this->getFieldOptions('file')->required()->notBlank()->toArray());

//        $builder->add('file', 'file', array('required' => true));

        // all crud actions have the same definition - no difference between view / add / edit

        //        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        //        $builder->add('description', 'textarea', CommonOptions::getOptional());
    }
}