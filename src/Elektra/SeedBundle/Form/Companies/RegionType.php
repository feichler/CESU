<?php

namespace Elektra\SeedBundle\Form\Companies;

use Elektra\CrudBundle\Form\Form as CrudForm;
use Elektra\SeedBundle\Form\CommonOptions;
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

        // all crud actions have the same definition - no difference between view / add / edit

        $builder->add('name', 'text', CommonOptions::getRequiredNotBlank());

        if ($options['crud_action'] == 'view') {
            //            $entity = $options['data'];
            //            echo $entity->getId();
            $builder->add('countries', 'relatedList',
                array(
                    'definition' => $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Country'),
                    'entity'     => $options['data'],
                    'relation'   => 'region',
                )
            );
        }
    }
}