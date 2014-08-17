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
 * Class SalesTeamController
 *
 * @package   Elektra\SeedBundle\Controller\Companies
 *
 * @version   0.1-dev
 */
class SalesTeamController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'SalesTeam');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_Companies_SalesTeam');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Companies/SalesTeam');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Companies\SalesTeam');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Companies\SalesTeam');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Companies\SalesTeamType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Companies\SalesTeamTable');

        // Set the crud section
        // TODO: set correct section
        $this->getOptions()->setSection('todo');
        $this->getOptions()->setType('sales_team');
    }
}