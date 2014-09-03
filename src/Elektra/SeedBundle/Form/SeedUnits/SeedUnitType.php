<?php

namespace Elektra\SeedBundle\Form\SeedUnits;

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
        $commonGroup = $this->getFieldGroup($builder, $options, 'Common Data'); // TRANSLATE this
        $builder->add($commonGroup);

        $commonGroup->add('serialNumber', 'text', CommonOptions::getRequiredNotBlank());

        $commonGroup->add(
            'model',
            'entity',
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model')->getClassEntity(),
                'property' => 'title'
            )
        );

        $commonGroup->add(
            'powerCordType',
            'entity',
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType')->getClassEntity(),
                'property' => 'title'
            )
        );

        if ($options['crud_action'] == 'add') {
            $commonGroup->add(
                'location',
                'entity',
                array(
                    'mapped'     => false,
                    'class' => $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation')->getClassEntity(),
                    'property' => 'locationIdentifier',
                )
            );
        }

        if ($options['crud_action'] != 'add')
        {
            $commonGroup->add('location', 'entity',
                array(
                    'mapped'    => true,
                    'read_only' => true,
                    'class' => $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity(),
                    'property' => 'title',
                )
            );

            $commonGroup->add('unitStatus', 'entity',
                array(
                    'mapped'    => true,
                    'read_only' => true,
                    'class' => $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassEntity(),
                    'property' => 'title',
                )
            );

            $commonGroup->add('changeStatus', 'submit',
                array(
                    'attr' =>
                        array(
                            'label' => "Change Status",
                        )
                )
            );

            $builder->add($this->createHistoryGroup($builder, $options));
        }
    }

    private function createHistoryGroup(FormBuilderInterface $builder, array $options)
    {
        $historyGroup = $this->getFieldGroup($builder, $options, 'History'); // TRANSLATE this

        $historyGroup->add('location', 'entity',
            array(
                'mapped'    => true,
                'read_only' => true,
                'class' => $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Location')->getClassEntity(),
                'property' => 'title',
            )
        );

        $historyGroup->add('unitStatus', 'entity',
            array(
                'mapped'    => true,
                'read_only' => true,
                'class' => $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus')->getClassEntity(),
                'property' => 'title',
            )
        );

        $historyGroup->add('changeStatus', 'submit',
            array(
                'attr' =>
                    array(
                        'label' => "Change Status",
                    )
            )
        );

        $historyGroup->add('events', 'relatedList',
            array(
                'relation_parent_entity' => $options['data'],
                'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'Event'),
            )
        );

        return $historyGroup;
    }
}