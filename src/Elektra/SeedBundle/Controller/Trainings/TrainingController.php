<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Trainings;

use Elektra\SeedBundle\Controller\CRUDController;

/**
 * Class TrainingController
 *
 * @package Elektra\SeedBundle\Controller\Trainings
 *
 * @version 0.1-dev
 */
class TrainingController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function loadDefinition()
    {

        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Trainings', 'Training');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_MasterData_Trainings_Training');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Trainings/Training');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Trainings\Training');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Trainings\Training');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Trainings\TrainingType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Trainings\TrainingTable');

        // Set the crud section
        $this->getOptions()->setSection('master_data');
        $this->getOptions()->setType('training');
    }
}