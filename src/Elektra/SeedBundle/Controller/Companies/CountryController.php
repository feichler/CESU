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
 * Class CountryController
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
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Country');
    }

    protected function getBrowseFilters()
    {

      $filters = parent::getBrowseFilters();
        $filters['region']     = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Companies', 'Region');

        return $filters;
    }
}