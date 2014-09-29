<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Entity\Companies\Partner;

class CustomerTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $route = $this->getCrud()->getLinker()->getActiveRoute();

        $customer = $this->getColumns()->addTitleColumn('name');
        $customer->setFieldData(array('shortName', 'name'));
        $customer->setSearchable();
        $customer->setSortable()->setFieldSort('shortName');

        if ($route == 'customers') {
            $partner = $this->getColumns()->add('partner');
            $partner->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner'));
            $partner->setFieldData(array('partner.shortName', 'partner.name'));
            //            $partner->setSortable();
            //            $partner->setFilterable()->setFieldFilter('name');
            //            $partner->setFieldFilter('partner.name');
        }
    }

    protected function initialiseCustomFilters()
    {

        $route = $this->getCrud()->getLinker()->getActiveRoute();

        if ($route == 'customers') {
            $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner');
            $repository = $this->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
            $partners   = $repository->findAll();
            $choices    = array();

            foreach ($partners as $partner) {
                if ($partner instanceof Partner) {
                    if (!array_key_exists($partner->getPartnerType()->getName(), $choices)) {
                        $choices[$partner->getPartnerType()->getName()] = array();
                    }
                    $choices[$partner->getPartnerType()->getName()][$partner->getId()] = $partner->getName();
                }
            }

            $this->addCustomFilter('partner', 'choice', array('label' => '', 'empty_value' => 'Select Partner', 'choices' => $choices));
        }
    }

    protected function getCustomLoadFilter($options)
    {

        $route = $this->getCrud()->getLinker()->getActiveRoute();

        $filter = array();

        if ($route == 'customers') {
            switch ($options['name']) {
                case 'partner':
                    $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner');
                    $filterName = $this->getFilterFieldName($options);
                    //        echo $filterName.'<br />';
                    $fieldName = 'partners';
                    $value     = $this->getRequestData('custom-filters', $filterName);
                    //        echo $value.'<br />';
                    if ($value != '') {
                        $filter[$fieldName] = 'IN:' . $definition->getClassEntity() . ':' . $value;
                    }
                    break;
            }
        }

        return $filter;
    }
}