<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\SeedUnits;

use Elektra\SeedBundle\Controller\CRUDController;

/**
 * Class SeedUnitModelController
 *
 * @package   Elektra\SeedBundle\Controller\SeedUnits
 *
 * @version   0.1-dev
 */
class SeedUnitModelController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_SeedUnits_Model');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:SeedUnits/SeedUnitModel');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:SeedUnits\SeedUnitModel');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\SeedUnits\SeedUnitModelType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\SeedUnits\SeedUnitModelTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('seed_unit_model');
    }
}