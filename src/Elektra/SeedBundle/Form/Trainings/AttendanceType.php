<?php

namespace Elektra\SeedBundle\Form\Trainings;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttendanceType extends CrudForm
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

        $builder->add('person', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson')->getClassEntity(),
                'property' => 'title'
            ))
        );

        $builder->add('training', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Training')->getClassEntity(),
                'property' => 'title'
            ))
        );
    }
}