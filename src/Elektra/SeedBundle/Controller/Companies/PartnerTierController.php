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
 * Class PartnerTierController
 *
 * @package   Elektra\SeedBundle\Controller\Companies
 *
 * @version   0.1-dev
 */
class PartnerTierController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        //URGENT: change routing to master data

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_Companies_PartnerTier');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Companies/PartnerTier');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Companies\PartnerTier');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Companies\PartnerTier');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Companies\PartnerTierType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Companies\PartnerTierTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('partnertier');
    }
}