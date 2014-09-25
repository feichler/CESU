<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Symfony\Component\Form\FormBuilderInterface;

class CustomerType extends CrudForm
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

        if ($options['crud_action'] == 'view')
        {
            $partnerOptions = $this->getFieldOptions('partner')
                ->notMapped()
                ->add('data', $options['data']->getPartners()->first()->getTitle());
            $common->add('partner','display',$partnerOptions->toArray());
        }
        else
        {
            echo "route=".$this->getCrud()->getLinker()->getActiveRoute()."<br/>";

            if (in_array($this->getCrud()->getLinker()->getActiveRoute(), array('customer.edit', 'customer.add')))
            {
                $partnerOptions = $this->getFieldOptions('partner')
                    ->required()
                    ->notMapped()
                    ->add('class', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner')->getClassEntity())
                    ->add('property', 'title');

                $common->add('partner','entity',$partnerOptions->toArray());
            }
            else
            {
                $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner');
                $this->addParentField('common', $builder, $options, $parentDefinition, 'partner', false);
            }
        }

        $common->add('shortName', 'text', $this->getFieldOptions('shortName')->required()->notBlank()->toArray());
        $common->add('name', 'text', $this->getFieldOptions('name')->optional()->toArray());

        if ($options['crud_action'] == 'view') {
            $locationsGroup             = $this->addFieldGroup($builder, $options, 'locations');
            $locationsFieldOptions = $this->getFieldOptions('locations')
                ->add('relation_parent_entity', $options['data'])
                ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'))
                ->add('relation_name', 'company');
            $locationsGroup->add('locations', 'relatedList', $locationsFieldOptions->toArray());
            // URGENT find a solution to display the persons at the company view

            $personsGroup             = $this->addFieldGroup($builder, $options, 'persons');
            $personsFieldOptions = $this->getFieldOptions('persons', false)
                ->add('crud', $this->getCrud())
                ->add('relation_parent_entity', $options['data'])
                ->add('relation_child_type', $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'))
                ->add('relation_name', 'persons');
            $personsGroup->add('persons', 'list', $personsFieldOptions->toArray());
        }
    }
}