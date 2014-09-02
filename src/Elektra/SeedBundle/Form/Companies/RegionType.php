<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\CrudBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegionType extends CrudForm
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

        $regionGroup = $this->getFieldGroup($builder, $options, 'Region Data'); // TRANSLATE this
        $regionGroup->add('name', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add($regionGroup);

        if ($options['crud_action'] == 'view') {
            $countriesGroup = $this->getFieldGroup($builder, $options, 'Countries'); // TRANSLATE this
            $countriesGroup->add(
                'countries',
                'relatedList',
                array(
                    'relation_parent_entity' => $options['data'],
                    'relation_child_type'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Country'),
                )
            );
            $builder->add($countriesGroup);
        }
    }
}