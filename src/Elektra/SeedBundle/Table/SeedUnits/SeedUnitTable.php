<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\CrudBundle\Table\Table;

class SeedUnitTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $serial = $this->getColumns()->addTitleColumn('tables.seed_units.seed_unit.serial_number');
        $serial->setFieldData('serialNumber');
        $serial->setSearchable();
        $serial->setSortable();

        $model = $this->getColumns()->add('tables.seed_units.seed_unit.model');
        $model->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model'));
        $model->setFieldData('model.name');
        $model->setSortable();
        $model->setFilterable()->setFieldFilter('name');

        $power = $this->getColumns()->add('tables.seed_units.seed_unit.power_cord_type');
        $power->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'));
        $power->setFieldData('powerCordType.name');
        $power->setSortable();
        $power->setFilterable()->setFieldFilter('name');

        $status = $this->getColumns()->add('tables.seed_units.seed_unit.status');
        $status->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'UnitStatus'));
        $status->setFieldData('unitStatus.name');
        $status->setSortable();
        $status->setFilterable()->setFieldFilter('name');

        $location = $this->getColumns()->add('tables.seed_units.seed_unit.location');
        $location->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location'));
        $location->setFieldData('location.shortName');
        $location->setSortable();
        $location->setFilterable()->setFieldFilter('shortName');

        $request = $this->getColumns()->add('tables.seed_units.seed_unit.request');
        $request->setFieldData('request.requestNumber');
        $request->setSearchable();
        $request->setSortable();
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCustomFilters()
    {

        $this->addCustomFilter(
            'inUse',
            'choice',
            array(
                'empty_value' => 'In Use?',
                'choices'     => array(
                    'n' => 'No',
                    'y' => 'Yes',
                ),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getCustomLoadFilter($options)
    {

        $filter = array();

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