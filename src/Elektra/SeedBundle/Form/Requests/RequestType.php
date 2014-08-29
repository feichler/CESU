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
        if ($options['crud_action'] == 'view') {
            $builder->add('requestNumber', 'integer',
                array(
                    'label'     => 'Request No'
                )
            );
        }

        $builder->add('numberOfUnitsRequested', 'integer', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'label'     => 'Units requested'
            )
        ));

        $builder->add('company', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => CompanyDefinitions::getRequestingCompany()->getClassEntity(),
                'property' => 'title',
            )
        ));

        $builder->add('requesterPerson', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => CompanyDefinitions::getCompanyPerson()->getClassEntity(),
                'property' => 'title',
            )
        ));

        $builder->add('receiverPerson', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => CompanyDefinitions::getCompanyPerson()->getClassEntity(),
                'property' => 'title',
            )
        ));

        $builder->add('shippingLocation', 'entity', array_merge(CommonOptions::getOptional(),
            array(
                'class'    => CompanyDefinitions::getCompanyLocation()->getClassEntity(),
                'property' => 'title',
            )
        ));

        if ($options['crud_action'] == 'view') {
            $builder->add(
                'seedUnits',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'),
//                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'),
//                    'parent'       => $options['data'],
//                    'relationName' => 'request',
                )
            );
        }
    }
}