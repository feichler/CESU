<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyPersonType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
        $this->addParentField('common', $builder, $options, $parentDefinition, 'location');

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