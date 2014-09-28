<?php

namespace Elektra\SeedBundle\Import\Template;

use Elektra\SeedBundle\Import\Template;

class SeedUnit extends Template
{

    /**
     * @return array
     */
    public function getInformation()
    {

        return array(
            'Use this template to import Seed Units into the CESU database',
            'Seed Units imported are added to the defined warehouse and are in the status "A - Available"',
        );
    }
}