<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Trainings;

use Elektra\CrudBundle\Controller\Controller;

/**
 * Class TrainingController
 *
 * @package Elektra\SeedBundle\Controller\Trainings
 *
 * @version 0.1-dev
 */
class TrainingController extends Controller
{

    /**
     * {@inheritdoc}
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Trainings', 'Training');
    }
}