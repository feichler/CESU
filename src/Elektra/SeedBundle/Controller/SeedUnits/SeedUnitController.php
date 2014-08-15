<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Elektra\SeedBundle\Controller\CRUDController;

class SeedUnitController extends CRUDController
{
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_SeedUnits_SeedUnit');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:SeedUnits/SeedUnit');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnit');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnit');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\SeedUnits\SeedUnitType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('seed_unit');
    }
}