<?php

namespace Elektra\SeedBundle\Import\Template;

use Elektra\CrudBundle\Crud\Definition;
use Elektra\SeedBundle\Import\Processor;
use Elektra\SeedBundle\Import\Template;

class CompanyLocation extends Template
{

    /**
     * @return array
     */
    public function getInformation()
    {

        return array(
            'Use this template to import Locations for Partner Organizations and Customer Companies into the CESU database',
            'Company Name and Alias are both required if the company is not yet stored in the database',
            'Organization Type is required if the Company Type is "Partner" and the company is not yet stored in the database',
            'Associated Partner is required if the Company Type is "Customer" and the company is not yet stored in the database',
        );
    }
}
