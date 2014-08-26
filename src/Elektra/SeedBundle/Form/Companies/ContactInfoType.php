<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactInfoType extends CrudForm
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
        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('text', 'text', CommonOptions::getRequiredNotBlank());

        $builder->add('contactInfoType', 'entity',array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType')->getClassEntity(),
                'property'    => 'title',
            )
        ));

        // CHECK: only necessary until embedded lists are finished
        $builder->add('person', 'entity',array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Person')->getClassEntity(),
                'property'    => 'title',
            )
        ));
    }
}