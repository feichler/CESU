<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Entity\Companies\Company;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
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
        /** @var CompanyLocation $entity */
        $entity = $options['data'];

        if ($options['crud_action'] != 'add') {
            $companyTypeData = $entity->getCompany()->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());

            /** @var Company $parentEntity */
            $parentEntity     = $parentRepository->find($this->getCrud()->getParentId());
            $companyTypeData  = $parentEntity->getCompanyType();

            if ($parentEntity->getLocations()->count() == 0)
            {
                $entity->setIsPrimary(true);
            }
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType')
            ->notMapped()
            ->add('data', Helper::translate($companyTypeData));
        if ($options['crud_action'] != 'view')
        {
            $companyTypeFieldOptions->readOnly();
        }
        $commonGroup->add('companyType', 'text', $companyTypeFieldOptions->toArray());

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Company');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'company');

        $commonGroup->add(
            'shortName',
            'text',
            $this->getFieldOptions('shortName')->optional()->toArray()
        );

        $isPrimary = $entity->getIsPrimary();
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

            $personsFieldOptions = $this->getFieldOptions('persons')->add('relation_parent_entity', $entity)
                ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'))
                ->add('relation_name', 'location');
            $personsGroup->add('persons', 'relatedList', $personsFieldOptions->toArray());
        }
    }
}