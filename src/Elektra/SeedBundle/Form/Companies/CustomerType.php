<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CustomerType extends CrudForm
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
        $builder->add('shortName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('name', 'text', CommonOptions::getOptional());

        if ($options['crud_action'] == 'view') {
            $builder->add(
                'locations',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'),
                    'relation_name' => 'company',
//                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'),
//                    'parent'       => $options['data'],
//                    'relationName' => 'company',
                )
            );
        }
    }
}