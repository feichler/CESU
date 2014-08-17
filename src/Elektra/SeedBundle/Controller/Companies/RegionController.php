<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Companies;

use Elektra\SeedBundle\Controller\CRUDController;

/**
 * Class SeedUnitModelController
 *
 * @package   Elektra\SeedBundle\Controller\Companies
 *
 * @version   0.1-dev
 */
class RegionController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Region');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_Geographic_Region');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Companies/Region');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Companies\Region');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Companies\Region');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Companies\RegionType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Companies\RegionTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('region');
    }
}