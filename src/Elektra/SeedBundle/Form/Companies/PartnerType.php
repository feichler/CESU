<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PartnerType extends CrudForm
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

        $builder->add('partnerTier', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier')->getClassEntity(),
                'property'    => 'title',
            )
        ));

        $builder->add('unitsLimit', 'integer', CommonOptions::getOptional());

        if ($options['crud_action'] == 'view') {
            $builder->add(
                'locations',
                'relatedList',
                array(
                    'definition'   => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'),
                    'parent'       => $options['data'],
                    'relationName' => 'company',
                )
            );
        }
    }
}