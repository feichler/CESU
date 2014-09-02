<?php

namespace Elektra\SeedBundle\Form\Requests;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddUnitsType extends CrudForm
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
        $builder->add('seedUnits', 'entity',
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit')->getClassEntity(),
                'property' => 'title',
                'expanded' => true,
                'multiple' => true
            )
        );

        $builder->add('save', 'submit',
            array(
                'attr' =>
                    array(
                        'label' => $this->getButtonLabel('save'),
                        'class' => 'save',
                    )
            )
        );
    }
}