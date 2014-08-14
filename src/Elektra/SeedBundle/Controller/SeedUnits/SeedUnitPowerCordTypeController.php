<?php

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Elektra\SeedBundle\Controller\CRUDController;

class SeedUnitPowerCordTypeController extends CRUDController
{

    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_SeedUnits_PowerCordType');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:SeedUnits/PowerCordType');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnitPowerCordType');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\SeedUnits\SeedUnitPowerCordTypeType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitPowerCordTypeTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('seed_unit_power_cord_type');
    }
}