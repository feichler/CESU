<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\Events\UnitSalesStatus;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Events\UnitUsage;

class SeedUnitTable extends Table
{

    protected function initialiseActions()
    {

        $crud  = $this->getColumns()->getTable()->getCrud();
        $route = $crud->getLinker()->getActiveRoute();

        if ($route == 'request.view') {
            //            $this->disallowAction('add');
            $this->disallowAction('edit');
            $this->disallowAction('delete');
            //            $this->disallowAction('view');
        }

        if ($route == 'request.seedUnit.add') {
            $this->disallowAction('add');
            $this->disallowAction('edit');
            $this->disallowAction('view');
            $this->disallowAction('delete');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $crud  = $this->getColumns()->getTable()->getCrud();
        $route = $crud->getLinker()->getActiveRoute();

        //        $select = $this->getColumns()->addSelectColumn();

        $this->getColumns()->addTitleColumn('serial_number')
            ->setFieldData('serialNumber')
            ->setSearchable()
            ->setSortable()
            ->setName('serial');

        $this->getColumns()->add('model')
            ->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model'))
            ->setFieldData('model.name')
            ->setSortable()
            ->setFilterable()->setFieldFilter('name');

        $this->getColumns()->add('power_cord_type')
            ->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'))
            ->setFieldData('powerCordType.name')
            ->setSortable()
            ->setFilterable()->setFieldFilter('name');

        $shipping = $this->getColumns()->add('shipping')
            ->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus'))
            ->setFieldData('shippingStatus.name')
            ->setSortable();

        // URGENT need the implemented unit sales status classes first
        //        $sales = $this->getColumns()->add('sales');
        //        $sales->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitSalesStatus'));
        //        $sales->setFieldData('salesStatus.name');
        //        $sales->setSortable();

        $usage = $this->getColumns()->add('usage')
            ->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage'))
            ->setFieldData('unitUsage.title')
            ->setSortable();
        //        $usage->setFieldData('unitUsage.title');
        // Filter not working
        //        $usage->setFilterable()->setFieldFilter('name');

        $this->getColumns()->add('location')
            ->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location'))
            ->setFieldData('location.shortName')
            ->setSortable();
        // Filter not working
        //        $location->setFilterable()->setFieldFilter('shortName');

        $request = $this->getColumns()->add('request')
            ->setFieldData('request.requestNumber')
            ->setSearchable()
            ->setSortable()
            ->setName('request');

        if ($route == 'request.seedUnit.add') {
            $shipping->setHidden();
            $request->setHidden();
            $usage->setHidden();
        } else if ($route == 'request.view') {
            $request->setHidden();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCustomFilters()
    {

        $crud  = $this->getColumns()->getTable()->getCrud();
        $route = $crud->getLinker()->getActiveRoute();

        switch ($route) {
            case 'seedUnits':
                $this->addInUseFilter();
                $this->addWarehouseFilter();
                $this->addShippingStatusFilter();
                $this->addSalesStatusFilter();
                $this->addUnitUsageFilter();
                break;

            case 'request.seedUnit.add':
                $this->addInUseFilter(false);
                $this->addWarehouseFilter();
                break;
        }

        return;
        $visible = true;

        if ($route == 'request.seedUnit.add') {
            $visible = false;
        }

        $this->addCustomFilter(
            'inUse',
            'choice',
            array(
                'empty_value' => 'In Use?',
                'choices'     => array(
                    'n' => 'No',
                    'y' => 'Yes',
                ),
            ),
            $visible
        );

        if ($route == 'seedUnits') {
            $whDefinition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
            $repository   = $this->getCrud()->getService('doctrine')->getRepository($whDefinition->getClassRepository());
            $warehouses   = $repository->findAll();
            $choices      = array();
            foreach ($warehouses as $wh) {
                if ($wh instanceof WarehouseLocation) {
                    $choices[$wh->getId()] = $wh->getShortName();
                }
            }
            $this->addCustomFilter(
                'whlocation',
                'choice',
                array(
                    'label'       => '',
                    'empty_value' => 'Select Warehouse',
                    'choices'     => $choices,
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getCustomLoadFilter($options)
    {

        $crud  = $this->getColumns()->getTable()->getCrud();
        $route = $crud->getLinker()->getActiveRoute();

        $filter = array();

        if ($route == 'request.seedUnit.add') {
            $filter['request'] = 'NULL';
        } else if ($route == 'request.view') {
            $filter['request'] = $crud->getRequest()->get("id");
        }

        //        } else {
        switch ($options['name']) {
            case 'inUse':

                $filterName = $this->getFilterFieldName($options);
                $fieldName  = 'request';

                $value = $this->getRequestData('custom-filters', $filterName);

                if ($value == 'n') {

                    $filter[$fieldName] = 'NULL';
                } else if ($value == 'y') {

                    $filter[$fieldName] = 'NOT NULL';
                }
                break;

            case 'warehouse':
                $filterName = $this->getFilterFieldName($options);
                $fieldName  = 'location';

                $value = $this->getRequestData('custom-filters', $filterName);
                if ($value != '') {
                    $filter[$fieldName] = $value;
                }
                break;

            case 'shipping':
                $filterName = $this->getFilterFieldName($options);
                $fieldName  = 'shippingStatus';

                $value = $this->getRequestData('custom-filters', $filterName);
                if ($value != '') {
                    $filter[$fieldName] = $value;
                }
                break;

            case 'sales':
                break;

            case 'usage':
                $filterName = $this->getFilterFieldName($options);
                $fieldName  = 'unitUsage';

                $value = $this->getRequestData('custom-filters', $filterName);
                if ($value != '') {
                    $filter[$fieldName] = $value;
                }
                break;

            default:
                throw new \RuntimeException('Unknown filter "' . $options['name'] . '"');
                break;
        }

        //        }

        return $filter;
    }

    private function addInUseFilter($visible = true)
    {

        $this->addCustomFilter(
            'inUse',
            'choice',
            array(
                // TRANSLATE
                'empty_value' => 'In Use?',
                'choices'     => array(
                    'n' => 'No',
                    'y' => 'Yes',
                ),
            ),
            $visible
        );
    }

    private function addWarehouseFilter()
    {

        $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
        $repository = $this->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
        $warehouses = $repository->findAll();
        $choices    = array();
        foreach ($warehouses as $wh) {
            if ($wh instanceof WarehouseLocation) {
                $choices[$wh->getId()] = $wh->getShortName();
            }
        }
        $this->addCustomFilter(
            'warehouse',
            'choice',
            array(
                'label'       => '',
                // TRANSLATE
                'empty_value' => 'Select Warehouse',
                'choices'     => $choices,
            )
        );
    }

    private function addShippingStatusFilter()
    {

        $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus');
        $repository = $this->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
        $statuses   = $repository->findAll();
        $choices    = array();

        foreach ($statuses as $status) {
            if ($status instanceof UnitStatus) {
                $choices[$status->getId()] = $status->getName();
            }
        }

        $this->addCustomFilter(
            'shipping',
            'choice',
            array(
                'label'       => '',
                // TRANSLATE
                'empty_value' => 'Select Shipping Status',
                'choices'     => $choices,
            )
        );
    }

    private function addSalesStatusFilter()
    {

        // URGENT need the implemented unit sales status classes first
        return;
        $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitSalesStatus');
        $repository = $this->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
        $statuses   = $repository->findAll();
        $choices    = array();

        foreach ($statuses as $status) {
            if ($status instanceof UnitSalesStatus) {
                $choices[$status->getId()] = $status->getName();
            }
        }

        $this->addCustomFilter(
            'sales',
            'choice',
            array(
                'label'       => '',
                // TRANSLATE
                'empty_value' => 'Select Sales Status',
                'choices'     => $choices,
            )
        );
    }

    private function addUnitUsageFilter()
    {

        $definition = $this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage');
        $repository = $this->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
        $usages     = $repository->findAll();
        $choices    = array();
        foreach ($usages as $status) {
            if ($status instanceof UnitUsage) {
                $choices[$status->getId()] = $status->getName();
            }
        }

        $this->addCustomFilter(
            'usage',
            'choice',
            array(
                'label'       => '',
                // TRANSLATE
                'empty_value' => 'Select Unit Usage',
                'choices'     => $choices,
            )
        );
    }
}