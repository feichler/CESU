<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Elektra\CrudBundle\Controller\Controller;

class SeedUnitController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
    }
}