<?php
/**
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Controller\Trainings;

use Elektra\SeedBundle\Controller\CRUDController;

/**
 * Class AttendanceController
 *
 * @package Elektra\SeedBundle\Controller\Trainings
 *
 *          @version 0.1-dev
 */
class AttendanceController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_Trainings_Attendance');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Trainings/Attendance');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Trainings\Attendance');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Trainings\Attendance');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Trainings\AttendanceType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Trainings\AttendanceTable');

        // Set the crud section
        // TODO: set correct section
        $this->getOptions()->setSection('todo');
        $this->getOptions()->setType('attendance');
    }

    /**
     *
     */
    protected function loadDefinition()
    {
        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Trainings', 'Attendance');
    }
}