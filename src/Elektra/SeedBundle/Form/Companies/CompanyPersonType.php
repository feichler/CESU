<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyPersonType extends CrudForm
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
        $parentDefinition = $this->getCrud()->getNavigator()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
        $this->addParentField($builder, $options, $parentDefinition, 'location');

        $builder->add('firstName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('lastName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('salutation', 'text', CommonOptions::getOptional());
        $builder->add('jobTitle', 'text', CommonOptions::getOptional());
        $builder->add('isPrimary', 'checkbox', CommonOptions::getOptional());

//        $builder->add('location', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
//            array(
//                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation')->getClassEntity(),
//                'property'    => 'title'
//            )
//        ));

        if ($options['crud_action'] == 'view') {
            $builder->add(
                'contactInfo',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo'),
                    'relation_name' => 'person',
//                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo'),
//                    'parent'       => $options['data'],
//                    'relationName' => 'person',
                )
            );
        }
    }
}