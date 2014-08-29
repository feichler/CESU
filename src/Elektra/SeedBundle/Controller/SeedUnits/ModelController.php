<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Elektra\CrudBundle\Controller\Controller;

class ModelController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model');
    }
}