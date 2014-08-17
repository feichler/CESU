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
 * Class RegistrationController
 *
 * @package Elektra\SeedBundle\Controller\Trainings
 *
 *          @version 0.1-dev
 */
class RegistrationController extends CRUDController
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseCRUD()
    {

        // Set the prefixes
        $this->getOptions()->setPrefix('route', 'ElektraSeedBundle_Trainings_Registration');
        $this->getOptions()->setPrefix('view', 'ElektraSeedBundle:Trainings/Registration');

        // Set the classes
        $this->getOptions()->setClass('entity', 'Elektra\SeedBundle\Entity\Trainings\Registration');
        $this->getOptions()->setClass('repository', 'ElektraSeedBundle:Trainings\Registration');
        $this->getOptions()->setClass('form', 'Elektra\SeedBundle\Form\Trainings\RegistrationType');
        $this->getOptions()->setClass('table', 'Elektra\SeedBundle\Table\Trainings\RegistrationTable');

        // Set the crud section
        // TODO: set correct section
        $this->getOptions()->setSection('todo');
        $this->getOptions()->setType('training');
    }

    /**
     *
     */
    protected function loadDefinition()
    {
        $this->definition = $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Trainings', 'Registration');
    }
}