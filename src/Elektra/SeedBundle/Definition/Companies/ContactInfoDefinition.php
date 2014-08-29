<?php

namespace Elektra\SeedBundle\Definition\Companies;

use Elektra\CrudBundle\Crud\Definition;

class ContactInfoDefinition extends Definition
{

    public function __construct()
    {

        parent::__construct('Elektra', 'Seed', 'Companies', 'ContactInfo');

        $this->setRouteSingular('contactInfo');

        $this->addParent('Elektra','Seed','Companies','CompanyPerson');
    }
}