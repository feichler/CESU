<?php

namespace Elektra\SeedBundle\Table\Events;

use Elektra\CrudBundle\Table\Table;

class EventTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $seedUnit = $this->getColumns()->add('seed_unit');
        $seedUnit->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
        $seedUnit->setFieldData('seedUnit.serialNumber');
        $seedUnit->setSortable();
        $seedUnit->setSearchable();

        $eventType = $this->getColumns()->addTitleColumn('event_type');
        $eventType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'EventType'));
        $eventType->setFieldData('eventType.name');
        $eventType->setSortable();
        $eventType->setFilterable()->setFieldFilter('name');

        $subject = $this->getColumns()->add('title');
        $subject->setFieldData('title');
        $subject->setSortable();
        $subject->setSearchable();

        $company = $this->getColumns()->add('company');
        $company->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'RequestingCompany'));
        $company->setFieldData(array('location.company.shortName', 'location.company.name'), true);
        $company->setSortable();
        $company->setSearchable();

        $location = $this->getColumns()->add('location');
        $location->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location'));
        $location->setFieldData(array('location.shortName', 'location.name'), true);
        $location->setSortable();
        $location->setFilterable()->setFieldFilter('shortName');

        $timestamp = $this->getColumns()->addDateColumn('date');
        $timestamp->setFieldData('timestamp');
        $timestamp->setSearchable();
        $timestamp->setSortable();

        if ($this->getCrud()->isEmbedded()) {
            if ($this->getCrud()->getParentDefinition()->getName() == 'SeedUnit') {
                $seedUnit->setHidden();
            }
        }
    }
}