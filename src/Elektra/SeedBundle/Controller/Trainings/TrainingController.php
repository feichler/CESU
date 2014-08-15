<?php

namespace Elektra\SeedBundle\Controller\Trainings;

use Elektra\SeedBundle\Controller\CRUDController;

class TrainingController extends CRUDController
{
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