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

        $timestamp = $this->getColumns()->addDateColumn('table.events.event.timestamp');
        $timestamp->setFieldData('timestamp');
        $timestamp->setSearchable();
        $timestamp->setSortable();

        $seedUnit = $this->getColumns()->add('table.events.event.seedUnit');
        $seedUnit->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit'));
        $seedUnit->setFieldData('seedUnit.serialNumber');
        $seedUnit->setSortable();
        $seedUnit->setSearchable();

        $eventType = $this->getColumns()->add('table.events.event.eventType');
        $eventType->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Events', 'EventType'));
        $eventType->setFieldData('eventType.name');
        $eventType->setSortable();
        $eventType->setFilterable()->setFieldFilter('name');

        $subject = $this->getColumns()->add('table.events.event.title');
        $subject->setFieldData('title');
        $subject->setSortable();
        $subject->setSearchable();

        $company = $this->getColumns()->add('table.events.event.company');
        $company->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'RequestingCompany'));
        $company->setFieldData(array('location.company.shortName', 'location.company.name'));
        $company->setSortable();
        $company->setSearchable();

        $location = $this->getColumns()->add('table.events.event.location');
        $location->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Location'));
        $location->setFieldData(array('shortName', 'name'));
        $location->setSortable();
        $location->setFilterable()->setFieldFilter('shortName');
    }
}