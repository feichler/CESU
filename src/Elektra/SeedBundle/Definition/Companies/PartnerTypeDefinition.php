<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class PartnerTypeDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'PartnerType');

        // single route (add / view / edit / delete)
        $this->setRouteSingular('partnerType');

        // root definition -> need a plural route
        $this->setRoot();
        $this->setRoutePlural('partnerTypes');
    }
}