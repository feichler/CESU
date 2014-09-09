<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\CommonOptions;
use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartnerType extends CrudForm
{

    /**
     * {@inheritdoc}
     */
    protected function getUniqueEntityFields()
    {

        return array(
            'shortName',
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function buildSpecificForm(FormBuilderInterface $builder, array $options)
    {

        $common = $this->addFieldGroup($builder, $options, 'common');

        $common->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $common->add('name', 'text', $this->getFieldOptions('name')->optional()->toArray());
/*        $partnerTierFieldOptions = $this->getFieldOptions('partnerTier')->required()->notBlank();
        $partnerTierFieldOptions->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier')->getClassEntity());
        $partnerTierFieldOptions->add('property', 'title');
        $common->add('partnerTier', 'entity', $partnerTierFieldOptions->toArray());*/
//        $common->add('unitsLimit', 'integer', $this->getFieldOptions('unitsLimit')->optional()->toArray());

        if ($options['crud_action'] == 'view') {
            $locations             = $this->addFieldGroup($builder, $options, 'locations');
            $locationsFieldOptions = $this->getFieldOptions('locations');
            $locationsFieldOptions->add('relation_parent_entity', $options['data']);
            $locationsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'));
            $locationsFieldOptions->add('relation_name', 'company');
            $locations->add('locations', 'relatedList', $locationsFieldOptions->toArray());
            // URGENT find a solution to display the persons at the company view

            $persons             = $this->addFieldGroup($builder, $options, 'persons');
            $personsFieldOptions = $this->getFieldOptions('persons', false);
            $personsFieldOptions->add('crud', $this->getCrud());
            $personsFieldOptions->add('relation_parent_entity', $options['data']);
            $personsFieldOptions->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
            $personsFieldOptions->add('relation_name', 'persons');
            $persons->add('persons', 'list', $personsFieldOptions->toArray());
        }
    }
}