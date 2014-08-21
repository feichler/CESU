<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Requests;

use Elektra\SeedBundle\Controller\CRUDController;

/**
 * Class SalesTeamController
 *
 * @package   Elektra\SeedBundle\Controller\Requests
 *
 * @version   0.1-dev
 */
class RequestCompletionController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Requests', 'RequestCompletion');
    }
}