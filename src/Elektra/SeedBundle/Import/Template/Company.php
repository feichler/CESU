<?php

namespace Elektra\SeedBundle\Import\Template;

use Elektra\SeedBundle\Import\Template;

class Company extends Template
{

    /**
     * @return array
     */
    public function getInformation()
    {

        return array(
            'Use this template to import Partner Organizations and Customer Companies into the CESU database',
        );
    }
}