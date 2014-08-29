<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
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

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Person');
        $this->addParentField($builder, $options, $parentDefinition, 'person');

        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('text', 'text', CommonOptions::getRequiredNotBlank());

        $builder->add(
            'contactInfoType',
            'entity',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType')->getClassEntity(),
                    'property' => 'title',
                )
            )
        );
    }
}