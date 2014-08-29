<?php

namespace Elektra\SeedBundle\Form\Trainings;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TrainingType extends CrudForm
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

        // all crud actions have the same definition - no difference between view / add / edit

        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());

        $builder->add('location', 'text', CommonOptions::getOptional());

        $dateOptions = array(
            'required' => true,
            'input'    => 'timestamp'
        );

        // TODO: format input fields (at least to single line)
        $builder->add('startedAt', 'datetime', $dateOptions);
        $builder->add('endedAt', 'datetime', $dateOptions);

        if ($options['crud_action'] == 'view') {
            $builder->add(
                'attendances',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Attendance'),
//                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Attendance'),
//                    'parent'       => $options['data'],
//                    'relationName' => 'training',
                )
            );

            $builder->add(
                'registrations',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Registration'),
//                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Trainings', 'Registration'),
//                    'parent'       => $options['data'],
//                    'relationName' => 'training',
                )
            );
        }
    }
}