<?php

namespace Elektra\SeedBundle\Form;

use Elektra\SiteBundle\Navigator\Definition;
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

    public function addFilter($name, Definition $definition)
    {

        $this->filters[$name] = $definition;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $data = $options['data'];

//        var_dump($data);
        foreach ($this->filters as $name => $definition) {
            $builder->add(
                $name,
                'entity',
                array(
                    'label'       => false,
                    'empty_value' => '- Please Select ?? - ', // TRANSLATE select option for filters
                    'class'       => $definition->getClassEntity(),
                    'property'    => 'name', // URGENT make property field for filters generic, maybe add to definition
                    'attr'        => array(
                        'onchange' => 'jQuery(this).closest(\'form\').trigger(\'submit\');',
                    ),
                )
            );
        }
    }

    //    public function addFilter()
    //    {
    //        $this->filters['seedunitmodel'] = '';
    //    }
    //
    //    public function buildForm(FormBuilderInterface $builder, array $options)
    //    {
    //
    //        foreach ($this->filters as $key => $filter) {
    //
    //            $builder->add(
    //                $key,
    //                'entity',
    //                array(
    //                    'label' => false,
    //                    'empty_value' => '- Select Seed Unit Model -',
    //                    'class'    => 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel',
    //                    'property' => 'name',
    //                    'attr' => array(
    //                        'onchange' => 'jQuery(this).closest(\'form\').trigger(\'submit\');',
    //                    ),
    //                )
    //            );
    //        }
    //    }
}