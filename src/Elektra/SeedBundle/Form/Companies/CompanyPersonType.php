<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SiteBundle\Site\Helper;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyPersonType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');
        $def    = $this->getCrud()->getParentDefinition();
        if ($def->getName() == 'Partner' || $def->getName() == 'Customer') {
            $this->buildCompanyForm($builder, $options, $common);
        } else {
            $this->buildLocationForm($builder, $options, $common);
        }
    }

    private function buildCompanyForm(FormBuilderInterface $builder, array $options, FormBuilderInterface $common)
    {

        $parentDefinition = $this->getCrud()->getParentDefinition();

        if ($options['crud_action'] != 'add') {
            $company         = $options['data']->getLocation()->getCompany();
            $companyData     = $company->getTitle();
            $companyTypeData = $company->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());
            $company          = $parentRepository->find($this->getCrud()->getParentId());
            $companyData      = $company->getTitle();
            $companyTypeData  = $company->getCompanyType();
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType')
            ->notMapped()
            ->add('data', Helper::translate($companyTypeData));

        $companyFieldOptions = $this->getFieldOptions('company')
            ->notMapped()
            ->add('data', $companyData);

        if ($options['crud_action'] != 'view')
        {
            $companyTypeFieldOptions->readOnly();
            $companyFieldOptions->readOnly();
        }

        $common->add('companyType', 'text', $companyTypeFieldOptions->toArray());
        $common->add('company', 'text', $companyFieldOptions->toArray());

        $locationFieldOptions = $this->getFieldOptions('location')
            ->required()
            ->notBlank()
            ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity())
            ->add('property', 'title')
            ->add('choices', $company->getLocations());
        $common->add('location', 'entity', $locationFieldOptions->toArray());

        $this->buildCommonForm($builder, $options, $common);
    }

    private function buildLocationForm(FormBuilderInterface $builder, array $options, FormBuilderInterface $common)
    {

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');

        if ($options['crud_action'] != 'add') {
            $companyData     = $options['data']->getLocation()->getCompany()->getTitle();
            $companyTypeData = $options['data']->getLocation()->getCompany()->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());
            $parentEntity     = $parentRepository->find($this->getCrud()->getParentId());
            $companyData      = $parentEntity->getCompany()->getTitle();
            $companyTypeData  = $parentEntity->getCompany()->getCompanyType();
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType');
        $companyFieldOptions     = $this->getFieldOptions('company');
        $companyTypeFieldOptions->notMapped();
        $companyFieldOptions->notMapped();
        if ($options['crud_action'] != 'view') {
            $companyTypeFieldOptions->readOnly();
            $companyFieldOptions->readOnly();
        }
        $companyTypeFieldOptions->add('data', Helper::translate($companyTypeData));
        $companyFieldOptions->add('data', $companyData);
        $common->add('companyType', 'text', $companyTypeFieldOptions->toArray());
        $common->add('company', 'text', $companyFieldOptions->toArray());

        $this->addParentField('common', $builder, $options, $parentDefinition, 'location');

        $this->buildCommonForm($builder, $options, $common);
    }

    private function buildCommonForm(FormBuilderInterface $builder, array $options, FormBuilderInterface $common)
    {

        $common->add('firstName', 'text', $this->getFieldOptions('firstName')->required()->notBlank()->toArray());
        $common->add('lastName', 'text', $this->getFieldOptions('lastName')->required()->notBlank()->toArray());
        $common->add('salutation', 'text', $this->getFieldOptions('salutation')->optional()->toArray());
        $common->add('jobTitle', 'text', $this->getFieldOptions('jobTitle')->optional()->toArray());
        $common->add('isPrimary', 'checkbox', $this->getFieldOptions('isPrimary')->optional()->toArray());

        if ($options['crud_action'] == 'view') {
            $contactInfos             = $this->addFieldGroup($builder, $options, 'contactInfos');
            $contactInfosFieldOptions = $this->getFieldOptions('contactInfos');
            $contactInfosFieldOptions->add('relation_parent_entity', $options['data']);
            $contactInfosFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo'));
            $contactInfosFieldOptions->add('relation_name', 'person');
            $contactInfos->add('contactInfo', 'relatedList', $contactInfosFieldOptions->toArray());
        }
    }
}