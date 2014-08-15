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
class CountryController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_Geographic_Country');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Companies/Country');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Companies\Country');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Companies\Country');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Companies\CountryType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Companies\CountryTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('country');
    }
}