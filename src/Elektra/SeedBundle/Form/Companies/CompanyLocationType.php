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

        $common = $this->addFieldGroup($builder, $options, 'common');

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'company');
        $common->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $common->add('name', 'text', $this->getFieldOptions('name')->optional()->toArray());
        $common->add('isPrimary', 'checkbox', $this->getFieldOptions('isPrimary')->optional()->toArray());

        $address                 = $this->addFieldGroup($builder, $options, 'address');
        $addressTypeFieldOptions = $this->getFieldOptions('addressType')->required()->notBlank();
        $addressTypeFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType')->getClassEntity());
        $addressTypeFieldOptions->add('property', 'title');
        $address->add('addressType', 'entity', $addressTypeFieldOptions->toArray());
        $addressFieldOptions = $this->getFieldOptions('address', false);
        $addressFieldOptions->add('data_class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address')->getClassEntity());
        $addressFieldOptions->add('crud_action', $options['crud_action']);
        $addressFieldOptions->add('default_actions', false);
        $address->add('address', new AddressType($this->getCrud()), $addressFieldOptions->toArray());

        if ($options['crud_action'] == 'view') {
            $persons             = $this->addFieldGroup($builder, $options, 'persons');
            $personsFieldOptions = $this->getFieldOptions('persons');
            $personsFieldOptions->add('relation_parent_entity', $options['data']);
            $personsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
            $personsFieldOptions->add('relation_name', 'location');
            $persons->add('persons', 'relatedList', $personsFieldOptions->toArray());
        }
    }
}