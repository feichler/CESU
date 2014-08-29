<?php

namespace Elektra\SeedBundle\Controller\Companies;

use Elektra\CrudBundle\Controller\Controller;

class CustomerController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Customer');
    }
}