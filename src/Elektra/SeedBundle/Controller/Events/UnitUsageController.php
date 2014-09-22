<?php

namespace Elektra\SeedBundle\Controller\Events;

use Elektra\CrudBundle\Controller\Controller;

class UnitUsageController extends Controller
{

    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Events', 'UnitUsage');
    }
}