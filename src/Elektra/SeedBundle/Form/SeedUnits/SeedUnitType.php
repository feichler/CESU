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

        $builder->add($commonGroup);
    }
}