<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CommonBundle\Form\DataTransformer\ToUppercaseTransformer;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartnerTierType extends CrudForm
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
        $builder->add($builder->create('name', 'text', CommonOptions::getRequiredNotBlank())->addModelTransformer(new ToUppercaseTransformer()));
        $builder->add('unitsLimit', 'integer', CommonOptions::getRequiredNotBlank());
    }
}