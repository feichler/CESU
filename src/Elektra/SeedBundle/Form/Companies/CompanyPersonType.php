<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Entity\Companies\Company;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
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
            /** @var Company $company */
            $company         = $options['data']->getLocation()->getCompany();
            $companyData     = $company->getTitle();
            $companyTypeData = $company->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());
            /** @var Company $company */
            $company          = $parentRepository->find($this->getCrud()->getParentId());
            $companyData      = $company->getTitle();
            $companyTypeData  = $company->getCompanyType();
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType')->notMapped()->add('data', Helper::translate($companyTypeData));

        $companyFieldOptions = $this->getFieldOptions('company')->notMapped()->add('data', $companyData);

        if ($options['crud_action'] != 'view') {
            $companyTypeFieldOptions->readOnly();
            $companyFieldOptions->readOnly();
        }

        $common->add('companyType', 'text', $companyTypeFieldOptions->toArray());
        $common->add('company', 'text', $companyFieldOptions->toArray());

        $locationFieldOptions = $this->getFieldOptions('location')->required()->notBlank()->add(
                'class',
                $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity()
            )->add('property', 'title')->add('choices', $company->getLocations());
        $common->add('location', 'entity', $locationFieldOptions->toArray());

        $this->buildCommonForm($builder, $options, $common, $company);
    }

    private function buildLocationForm(FormBuilderInterface $builder, array $options, FormBuilderInterface $common)
    {

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
        /** @var CompanyPerson $entity */
        $entity = $options['data'];
        if ($options['crud_action'] != 'add') {
            $company = $entity->getLocation()->getCompany();
            $companyData     = $company->getTitle();
            $companyTypeData = $company->getCompanyType();
        } else {
            $parentRepository = $this->getCrud()->getService('doctrine')->getRepository($parentDefinition->getClassRepository());
            /** @var CompanyLocation $parentEntity */
            $parentEntity     = $parentRepository->find($this->getCrud()->getParentId());
            $company = $parentEntity->getCompany();
            $companyData      = $company->getTitle();
            $companyTypeData  = $company->getCompanyType();
        }

        $companyTypeFieldOptions = $this->getFieldOptions('companyType')
            ->notMapped()
            ->add('data', Helper::translate($companyTypeData));
        $companyFieldOptions = $this->getFieldOptions('company')
            ->notMapped()
            ->add('data', $companyData);

        if ($options['crud_action'] != 'view') {
            $companyTypeFieldOptions->readOnly();
            $companyFieldOptions->readOnly();
        }

        $common->add('companyType', 'text', $companyTypeFieldOptions->toArray());
        $common->add('company', 'text', $companyFieldOptions->toArray());

        $this->addParentField('common', $builder, $options, $parentDefinition, 'location');

        $this->buildCommonForm($builder, $options, $common, $company);
    }

    private function buildCommonForm(FormBuilderInterface $builder, array $options, FormBuilderInterface $common, Company $company)
    {

        $common->add('firstName', 'text', $this->getFieldOptions('firstName')->required()->notBlank()->toArray());
        $common->add('lastName', 'text', $this->getFieldOptions('lastName')->required()->notBlank()->toArray());
        $common->add('salutation', 'text', $this->getFieldOptions('salutation')->optional()->toArray());
        $common->add('jobTitle', 'text', $this->getFieldOptions('jobTitle')->optional()->toArray());

        /** @var CompanyPerson $entity */
        $entity = $options['data'];

        if ($options['crud_action'] == 'add')
        {
            $first = true;
            foreach ($company->getLocations() as $otherLocation)
            {
                /** @var CompanyLocation $otherLocation */
                if ($otherLocation->getPersons()->count() > 0)
                {
                    $first = false;
                    break;
                }
            }

            if ($first)
            {
                $entity->setIsPrimary(true);
            }
        }

        $isPrimary = $entity->getIsPrimary();
        if ($isPrimary) {
            $isPrimaryOptions = $this->getFieldOptions('isPrimary');
            $common->add('isPrimary', 'hidden', $isPrimaryOptions->toArray());
            $isPrimaryInfoOptions = $this->getFieldOptions('isPrimary')->notMapped()->add('data', $isPrimary)->add('disabled', true);
            $common->add('isPrimaryInfo', 'checkbox', $isPrimaryInfoOptions->toArray());
        } else {
            $isPrimaryOptions = $this->getFieldOptions('isPrimary')->optional();
            $common->add('isPrimary', 'checkbox', $isPrimaryOptions->toArray());
        }

        if ($options['crud_action'] == 'view') {
            $contactInfoGroup             = $this->addFieldGroup($builder, $options, 'contactInfos');
            $contactInfoFieldOptions = $this->getFieldOptions('contactInfos')
                ->add('relation_parent_entity', $options['data'])
                ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo'))
                ->add('relation_name', 'person');
            $contactInfoGroup->add('contactInfo', 'relatedList', $contactInfoFieldOptions->toArray());
        }
    }
}