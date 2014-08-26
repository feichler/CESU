<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressType extends CrudForm
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
        //echo var_dump($options);
        $builder->add('street1', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('street2', 'text', CommonOptions::getOptional());
        $builder->add('street3', 'text', CommonOptions::getOptional());
        $builder->add('postalCode', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('city', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('state', 'text', CommonOptions::getOptional());
        $builder->add('country', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'       => 'Elektra\SeedBundle\Entity\Companies\Country',
                'property'    => 'title',
            ))
        );
    }
}