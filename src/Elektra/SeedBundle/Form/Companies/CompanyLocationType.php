<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyLocationType extends CrudForm
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
        // CHECK: only necessary until embedded lists are finished
        $builder->add('company', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => 'Elektra\SeedBundle\Entity\Companies\Company',
                'property' => 'shortName',
            )
        ));

        $builder->add('shortName', 'text', CommonOptions::getRequiredNotBlank());
        $builder->add('name', 'text', CommonOptions::getOptional());
        $builder->add('isPrimary', 'checkbox', CommonOptions::getOptional());

        $builder->add('addressType', 'entity', array_merge(CommonOptions::getRequiredNotBlank(),
            array(
                'class'    => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'AddressType')->getClassEntity(),
                'property' => 'title',
            )
        ));

        $addressOptions = $options;
        $addressOptions['data'] = $options['data']->getAddress();
        $addressOptions['data_class'] = $this->getCrud()->getDefinition('Elektra','Seed','Companies','Address')->getClassEntity();
        $builder->add("address", new AddressType($this->getCrud()), $addressOptions);

        //TODO: list input for persons
    }
}