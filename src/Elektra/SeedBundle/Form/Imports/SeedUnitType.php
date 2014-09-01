<?php

namespace Elektra\SeedBundle\Form\Imports;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeedUnitType extends CrudForm
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

        $builder->add('file', 'file', array('required' => true));

        // all crud actions have the same definition - no difference between view / add / edit

        //        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        //        $builder->add('description', 'textarea', CommonOptions::getOptional());
    }
}