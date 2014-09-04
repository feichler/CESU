<?php

namespace Elektra\SeedBundle\Form\Requests;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Definition\CompanyDefinitions as CompanyDefinitions;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RequestType extends CrudForm
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

        $commonGroup = $this->addFieldGroup($builder, $options, 'common'); // TRANSLATE this

        if ($options['crud_action'] == 'view') {
            $commonGroup->add(
                'requestNumber',
                'integer',
                array(
                    'label' => $this->getFieldLabel('requestNumber'),
                    //                    'label'     => 'Request No',
                )
            );
        }

        $commonGroup->add(
            'numberOfUnitsRequested',
            'integer',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'label' => $this->getFieldLabel('numberOfUnitsRequested'),
                    //                'label'     => 'Units requested',
                )
            )
        );

        $commonGroup->add(
            'company',
            'entity',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'label' => $this->getFieldLabel('company'),
                    'class'    => CompanyDefinitions::getRequestingCompany()->getClassEntity(),
                    'property' => 'title',
                    'group_by'=>'companyType',
                )
            )
        );

        $commonGroup->add(
            'requesterPerson',
            'entity',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'label' => $this->getFieldLabel('requesterPerson'),
                    'class'    => CompanyDefinitions::getCompanyPerson()->getClassEntity(),
                    'property' => 'title',
                )
            )
        );

        $commonGroup->add(
            'receiverPerson',
            'entity',
            array_merge(
                CommonOptions::getRequiredNotBlank(),
                array(
                    'label' => $this->getFieldLabel('receiverPerson'),
                    'class'    => CompanyDefinitions::getCompanyPerson()->getClassEntity(),
                    'property' => 'title',
                )
            )
        );

        $commonGroup->add(
            'shippingLocation',
            'entity',
            array_merge(
                CommonOptions::getOptional(),
                array(
                    'label' => $this->getFieldLabel('shippingLocation'),
                    'class'    => CompanyDefinitions::getCompanyLocation()->getClassEntity(),
                    'property' => 'title',
                )
            )
        );
        $builder->add($commonGroup);

        if ($options['crud_action'] == 'view') {
            $unitsGroup =  $this->addFieldGroup($builder, $options, 'units'); // TRANSLATE this
            $unitsGroup->add(
                'seedUnits',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'),
                )
            );
            $builder->add($unitsGroup);
        }
    }
}