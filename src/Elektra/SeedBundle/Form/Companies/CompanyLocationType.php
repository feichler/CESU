<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\CommonOptions;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

        $commonGroup = $this->getFieldGroup($builder, $options, 'Common Data'); // TRANSLATE this

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField($commonGroup, $options, $parentDefinition, 'company');

        $commonGroup->add('shortName', 'text', CommonOptions::getRequiredNotBlank());
        $commonGroup->add('name', 'text', CommonOptions::getOptional());
        $commonGroup->add('isPrimary', 'checkbox', CommonOptions::getOptional());

        $builder->add($commonGroup);

        $addressGroup = $this->getFieldGroup($builder, $options, 'Address Data'); // TRANSLATE this
        $addressGroup->add(
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
            'show_buttons' => false,
            'label'        => false,
        );
        $addressGroup->add("address", new AddressType($this->getCrud()), $addressOptions);
        $builder->add($addressGroup);

        if ($options['crud_action'] == 'view') {
            $personsGroup = $this->getFieldGroup($builder, $options, 'Persons'); // TRANSLATE this
            $personsGroup->add(
                'persons',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'),
                    'relation_name'          => 'location',
                )
            );
            $builder->add($personsGroup);
        }
    }
}