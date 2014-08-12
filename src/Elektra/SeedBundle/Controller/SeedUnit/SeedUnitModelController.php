<?php

namespace Elektra\SeedBundle\Controller\SeedUnit;

use Elektra\SeedBundle\Controller\CRUDController;

class SeedUnitModelController extends CRUDController
{

    /**
     * @return void
     */
    protected function initialiseVariables()
    {

        // set the prefixes
        $this->setPrefix('routing', 'ElektraSeedBundle_seedunits_models');
        $this->setPrefix('view', 'ElektraSeedBundle:SeedUnit/SeedUnitModels');

        // set the language keys
        $this->setLangKey('type', 'seedunit_models');
        $this->setLangKey('section', 'master_data');

        // set the classes
        $this->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable');
        $this->setClass('form', 'Elektra\SeedBundle\Form\Type\SeedUnits\SeedUnitModelType');
        $this->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $this->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel');
    }
}