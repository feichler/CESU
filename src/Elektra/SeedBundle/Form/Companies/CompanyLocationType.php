<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyLocationType extends CrudForm
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

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField($builder, $options, $parentDefinition, 'company');

        $builder->add('shortName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('name', 'text', CommonOptions::getOptional());
        $builder->add('isPrimary', 'checkbox', CommonOptions::getOptional());

        $builder->add(
            'addressType',
            'entity',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType')->getClassEntity(),
                    'property' => 'title',
                )
            )
        );

        $addressOptions = array(
            'data_class'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address')->getClassEntity(),
            'crud_action'  => $options['crud_action'],
            'show_buttons' => false
        );
        $builder->add("address", new AddressType($this->getCrud()), $addressOptions);

        $builder->add(
            'persons',
            'relatedList',
            array(
                'relation_parent_entity' => $options['data'],
                'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'),
                'relation_name'          => 'location',
            )
        );
    }
}