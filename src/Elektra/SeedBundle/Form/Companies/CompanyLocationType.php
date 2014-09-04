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
    protected function getUniqueEntityFields()
    {

        return array(
            array('shortName', 'company'),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $commonGroup = $this->addFieldGroup($builder, $options, 'common');

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'company');

        $commonGroup->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $commonGroup->add('name', 'text', $this->getFieldOptions('name')->optional()->toArray());
        $commonGroup->add('isPrimary', 'checkbox', $this->getFieldOptions('isPrimary')->optional()->toArray());

        $addressGroup = $this->addFieldGroup($builder, $options, 'address');

        $addressTypeFieldOptions = $this->getFieldOptions('addressType')
            ->required()
            ->notBlank()
            ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType')->getClassEntity())
            ->add('property', 'title');
        $addressGroup->add('addressType', 'entity', $addressTypeFieldOptions->toArray());

        $addressFieldOptions = $this->getFieldOptions('address', false)
            ->add('data_class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address')->getClassEntity())
            ->add('crud_action', $options['crud_action'])
            ->add('default_actions', false);
        $addressGroup->add('address', new AddressType($this->getCrud()), $addressFieldOptions->toArray());

        if ($options['crud_action'] == 'view')
        {
            $personsGroup = $this->addFieldGroup($builder, $options, 'persons');

            $personsFieldOptions = $this->getFieldOptions('persons')
                ->add('relation_parent_entity', $options['data'])
                ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'))
                ->add('relation_name', 'location');
            $personsGroup->add('persons', 'relatedList', $personsFieldOptions->toArray());
        }
    }
}