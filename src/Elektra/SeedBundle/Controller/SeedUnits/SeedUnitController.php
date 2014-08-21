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
 * Class SeedUnitController
 *
 * @package   Elektra\SeedBundle\Controller\SeedUnits
 *
 * @version   0.1-dev
 */
class SeedUnitController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
    }

    protected function loadFilters() {

        $filters = array(
            'model' => $this->get('navigator')->getDefinition('Elektra','Seed','SeedUnits','SeedUnitMode'),
        );
    }
}