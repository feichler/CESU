<?php

namespace Elektra\SeedBundle\Controller\Companies;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\CrudBundle\Crud\Definition;

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