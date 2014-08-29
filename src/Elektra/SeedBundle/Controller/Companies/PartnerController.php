<?php

namespace Elektra\SeedBundle\Controller\Companies;

use Elektra\CrudBundle\Controller\Controller;

class PartnerController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Partner');
    }
}