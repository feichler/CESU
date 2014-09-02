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

        $commonGroup = $this->getFieldGroup($builder, $options, 'Common Data'); // TRANSLATE this
        $commonGroup->add('shortName', 'text', CommonOptions::getRequiredNotBlank());
        $commonGroup->add('name', 'text', CommonOptions::getOptional());
        $builder->add($commonGroup);

        if ($options['crud_action'] == 'view') {
            $locationsGroup = $this->getFieldGroup($builder, $options, 'Locations'); // TRANSLATE this
            $locationsGroup->add(
                'locations',
                'relatedList',
                array(
                    'label'                  => false,
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'),
                    'relation_name'          => 'company',
                )
            );
            $builder->add($locationsGroup);
            // URGENT find a solution to display the persons at the company view
        }
    }
}