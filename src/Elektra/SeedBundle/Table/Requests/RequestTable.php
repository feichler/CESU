<?php

namespace Elektra\SeedBundle\Table\Requests;

use Elektra\CrudBundle\Table\Table;

class RequestTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $requestNumber = $this->getColumns()->addTitleColumn('request_number');
        $requestNumber->setFieldData('requestNumber');
        $requestNumber->setSearchable();
        $requestNumber->setSortable();

        $company = $this->getColumns()->add('company');
        $company->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner'));
        $company->setFieldData(array('company.shortName', 'company.name'), true);
        $company->setSortable();
        $company->setFilterable()->setFieldFilter('shortName');

        $requester = $this->getColumns()->add('requester');
        $requester->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
        $requester->setFieldData('requesterPerson.title');
        $requester->setSortable();
        //        $requester->setFilterable()->setFieldFilter('name');

        $receiver = $this->getColumns()->add('receiver');
        $receiver->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
        $receiver->setFieldData('receiverPerson.title');
        $receiver->setSortable();
        //        $receiver->setFilterable()->setFieldFilter('name');

        $shippingAddress = $this->getColumns()->add('shipping_location');
        $shippingAddress->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'));
        $shippingAddress->setFieldData(array('shippingLocation.shortName', 'shippingLocation.name'), true);
        $shippingAddress->setSortable();

        $requestedUnits = $this->getColumns()->add('numberOfUnitsRequested');
        $requestedUnits->setFieldData('numberOfUnitsRequested');

        $assigned = $this->getColumns()->addCountColumn('numberOfUnitsAssigned');
        $assigned->setFieldData('seedUnits');
    }
}