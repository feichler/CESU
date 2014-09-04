<?php

namespace Elektra\SeedBundle\Definition\Auditing;

use Elektra\CrudBundle\Crud\Definition;

class AuditDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Auditing', 'Audit');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('note');

        // has valid parents
        $this->addParent('Elektra', 'Seed', 'Companies', 'Address');
        $this->addParent('Elektra', 'Seed', 'Companies', 'Partner');
        $this->addParent('Elektra', 'Seed', 'Companies', 'Customer');
        $this->addParent('Elektra', 'Seed', 'Companies', 'SalesTeam');
        $this->addParent('Elektra', 'Seed', 'Companies', 'RequestingCompany');
        $this->addParent('Elektra', 'Seed', 'Companies', 'ContactInfo');
        $this->addParent('Elektra', 'Seed', 'Companies', 'CompanyLocation');
        $this->addParent('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
        $this->addParent('Elektra', 'Seed', 'Companies', 'CompanyPerson');

        //        $this->addParent('Elektra', 'Seed', 'Events', 'ActivityEvent');
        //        $this->addParent('Elektra', 'Seed', 'Events', 'PartnerEvent');
        //        $this->addParent('Elektra', 'Seed', 'Events', 'ResponseEvent');
        //        $this->addParent('Elektra', 'Seed', 'Events', 'SalesEvent');
        //        $this->addParent('Elektra', 'Seed', 'Events', 'ShippingEvent');

        //        $this->addParent('Elektra', 'Seed', 'Requests', 'Request');
        //
        $this->addParent('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');

        // CHECK also add the training entities?
    }
}