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

        $requestNumber = $this->getColumns()->addTitleColumn('table.requests.request.requestNumber');
        $requestNumber->setFieldData('requestNumber');
        $requestNumber->setSearchable();
        $requestNumber->setSortable();

        $company = $this->getColumns()->add('table.requests.request.company');
        $company->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'RequestingCompany'));
        $company->setFieldData(array('shortName', 'name'));
        $company->setSortable();
        $company->setFilterable()->setFieldFilter('shortName');

        $requester = $this->getColumns()->add('table.requests.request.requester');
        $requester->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
        $requester->setFieldData('requester.title');
        $requester->setSortable();
//        $requester->setFilterable()->setFieldFilter('name');

        $receiver = $this->getColumns()->add('table.requests.request.receiver');
        $receiver->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyPerson'));
        $receiver->setFieldData('receiver.title');
        $receiver->setSortable();
//        $receiver->setFilterable()->setFieldFilter('name');

        $shippingAddress = $this->getColumns()->add('table.requests.request.shippingLocation');
        $shippingAddress->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation'));
        $company->setFieldData(array('shippingLocation.shortName', 'shippingLocation.name'));
        $shippingAddress->setSortable();

    }
}