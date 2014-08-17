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
 * Class WarehouseLocationController
 *
 * @package   Elektra\SeedBundle\Controller\Companies
 *
 * @version   0.1-dev
 */
class WarehouseLocationController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_Companies_WarehouseLocation');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Companies/WarehouseLocation');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Companies\WarehouseLocation');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Companies\WarehouseLocation');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Companies\WarehouseLocationType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Companies\WarehouseLocationTable');

        // Set the crud section
        // TODO: set correct section
        $this->getOptions()->setSection('todo');
        $this->getOptions()->setType('warehouselocation');
    }
}