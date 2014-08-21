<?php

namespace Elektra\SeedBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CRUDFilters extends AbstractType
{

    protected $filters;

    public function __construct()
    {

        $this->filters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {

        return 'crudfilters';
    }

    public function addFilter()
    {
        $this->filters['seedunitmodel'] = '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        foreach ($this->filters as $key => $filter) {

            $builder->add(
                $key,
                'entity',
                array(
                    'empty_value' => '- Select Seed Unit Model -',
                    'class'    => 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel',
                    'property' => 'name',
                    'attr' => array(
                        'onchange' => 'jQuery(this).closest(\'form\').trigger(\'submit\');',
                    ),
                )
            );
        }
    }
}