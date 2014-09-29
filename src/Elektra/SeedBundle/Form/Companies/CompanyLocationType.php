<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyLocationType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');

        $commonGroup = $this->addFieldGroup($builder, $options, 'common');

        if ($options['crud_action'] != 'add') {
            $companyTypeData = $options['data']->getCompany()->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());
            $parentEntity     = $parentRepository->find($this->getCrud()->getParentId());
            $companyTypeData  = $parentEntity->getCompanyType();
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType')->notMapped();
        if ($options['crud_action'] != 'view') {
            $companyTypeFieldOptions->readOnly();
        }
        $companyTypeFieldOptions->add('data', Helper::translate($companyTypeData));
        $commonGroup->add('companyType', 'text', $companyTypeFieldOptions->toArray());

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'company');

        $commonGroup->add(
            'shortName',
            'text',
            $this->getFieldOptions('shortName')->optional()->toArray()
        );

        $isPrimary = $options['data']->getIsPrimary();
        if ($isPrimary) {
            $isPrimaryOptions = $this->getFieldOptions('isPrimary');
            $commonGroup->add('isPrimary', 'hidden', $isPrimaryOptions->toArray());
            $isPrimaryInfoOptions = $this->getFieldOptions('isPrimary')->notMapped()->add('data', $isPrimary)->add('disabled', true);
            $commonGroup->add('isPrimaryInfo', 'checkbox', $isPrimaryInfoOptions->toArray());
        } else {
            $isPrimaryOptions = $this->getFieldOptions('isPrimary')->optional();
            $commonGroup->add('isPrimary', 'checkbox', $isPrimaryOptions->toArray());
        }

        $addressGroup = $this->addFieldGroup($builder, $options, 'address');

        $addressFieldOptions = $this->getFieldOptions('address', false)->add('data_class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Address')->getClassEntity())->add(
            'crud_action',
            $options['crud_action']
        )->add('default_actions', false);
        $addressGroup->add('address', new AddressType($this->getCrud()), $addressFieldOptions->toArray());

        if ($options['crud_action'] == 'view') {
            $personsGroup = $this->addFieldGroup($builder, $options, 'persons');

            $personsFieldOptions = $this->getFieldOptions('persons')->add('relation_parent_entity', $options['data'])->add(
                'relation_child_type',
                $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson')
            )->add('relation_name', 'location');
            $personsGroup->add('persons', 'relatedList', $personsFieldOptions->toArray());
        }
    }
}