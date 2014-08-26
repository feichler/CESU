<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CountryType extends CrudForm
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

        // all crud actions have the same definition - no difference between view / add / edit

        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('alphaTwo', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('alphaThree', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('numericCode', 'text', CommonOptions::getRequiredNotBlank());

        $regionOptions = array(
            'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region')->getClassEntity(),
            'property' => 'name',
        );
        $builder->add('region', 'entity', $regionOptions);
    }
}