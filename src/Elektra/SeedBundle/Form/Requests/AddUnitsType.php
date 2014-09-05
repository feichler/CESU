<?php

namespace Elektra\SeedBundle\Form\Requests;

use Doctrine\ORM\EntityRepository;
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
                'multiple' => true,
                'query_builder' => function(EntityRepository $er)
                    {
                        return $er->createQueryBuilder('su')
                            ->where("su.request is null");
                    }
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