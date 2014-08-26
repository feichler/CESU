<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyPersonType extends CrudForm
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
        // CHECK: only necessary until embedded lists are finished
        $builder->add('firstName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('lastName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('salutation', 'text', CommonOptions::getOptional());
        $builder->add('jobTitle', 'text', CommonOptions::getOptional());
        $builder->add('isPrimary', 'checkbox', CommonOptions::getOptional());

        $builder->add('location', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity(),
                'property'    => 'title'
            )
        ));
    }
}