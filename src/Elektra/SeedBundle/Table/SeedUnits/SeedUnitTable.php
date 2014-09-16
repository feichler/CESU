<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Doctrine\ORM\EntityManager;
use Elektra\CrudBundle\Table\Table;

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

        $serial = $this->getColumns()->addTitleColumn('serial_number');
        $serial->setFieldData('serialNumber');
        $serial->setSearchable();
        $serial->setSortable();

        $model = $this->getColumns()->add('model');
        $model->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model'));
        $model->setFieldData('model.name');
        $model->setSortable();
        $model->setFilterable()->setFieldFilter('name');

        $power = $this->getColumns()->add('power_cord_type');
        $power->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'));
        $power->setFieldData('powerCordType.name');
        $power->setSortable();
        $power->setFilterable()->setFieldFilter('name');

        $status = $this->getColumns()->add('status');
        $status->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus'));
        $status->setFieldData('shippingStatus.name');
        $status->setSortable();
        // Filter not working
//        $status->setFilterable()->setFieldFilter('name');

        $usage = $this->getColumns()->add('usage');
        $usage->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage'));
        $usage->setFieldData('unitUsage.title');
        $usage->setSortable();
        // Filter not working
//        $usage->setFilterable()->setFieldFilter('name');

        $location = $this->getColumns()->add('location');
        $location->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location'));
        $location->setFieldData('location.shortName');
        $location->setSortable();
        // Filter not working
        //        $location->setFilterable()->setFieldFilter('shortName');

        $request = $this->getColumns()->add('request');
        $request->setFieldData('request.requestNumber');
        $request->setSearchable();
        $request->setSortable();

        if ($route == 'request.seedUnit.add') {
            $status->setHidden();
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
        } else {
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

                default:
                    throw new \RuntimeException('Unknown filter "' . $options['name'] . '"');
                    break;
            }
        }

        //        var_dump($filter);
        return $filter;
    }
    //    /**
    //     * {@inheritdoc}
    //     */
    //
    //    protected function prepareCustomFilters()
    //    {
    //
    //        $filters = array();
    //
    //        $name  = 'inUse';
    //        $value = $this->container->get('request')->get($name, null);
    //
    //        if ($value == 0) {
    //        }
    //
    //        if ($value !== null && !empty($value)) {
    //            if ($value == 'n') {
    //                $filters['requestCompletion'] = 'NULL';
    //            } else if ($value == 'y') {
    //                $filters['requestCompletion'] = 'NOT NULL';
    //            }
    //            //            $filters[$name] = $value;
    //        }
    //
    //        return $filters;
    //    }
}