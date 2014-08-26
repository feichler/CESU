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

        $serial = $this->getColumns()->addTitleColumn('table.seed_units.seed_unit.serial');
        $serial->setFieldData('serialNumber');
        $serial->setSearchable();
        $serial->setSortable();

        $model = $this->getColumns()->add('table.seed_units.seed_unit.model');
        $model->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model'));
        $model->setFieldData('model.name');
        $model->setSortable();
        $model->setFilterable()->setFieldFilter('name');

        $power = $this->getColumns()->add('table.seed_units.seed_unit.power_cord_type');
        $power->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType'));
        $power->setFieldData('powerCordType.name');
        $power->setSortable();
        $power->setFilterable()->setFieldFilter('name');

        $request = $this->getColumns()->add('table.seed_units.seed_unit.request');
        $request->setFieldData('requestCompletion.request.requestNumber');
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
                $fieldName  = 'requestCompletion';

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