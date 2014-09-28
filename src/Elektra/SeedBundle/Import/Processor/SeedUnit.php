<?php

namespace Elektra\SeedBundle\Import\Processor;

use Elektra\SeedBundle\Controller\EventFactory;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Entity\Notes\Note;
use Elektra\SeedBundle\Entity\SeedUnits\Model;
use Elektra\SeedBundle\Entity\SeedUnits\PowerCordType;
use Elektra\SeedBundle\Import\Processor;

class SeedUnit extends Processor
{

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function checkAndPrepareRowData(array &$data, $row)
    {

        /*
         * Required checks:
         *  - serial number must not be in the database
         *  - warehouse must exist
         *
         * Required prepares:
         *  - load or create the model
         *  - load or create the power cord type
         *  - load the warehouse
         */

        $valid = true;

        // Serial number check
        if (!$this->isSerialNumberUsable($data['serial'])) {
            $msg = 'Serial Number "' . $data['serial'] . '" is already in use' ;
            $this->getType()->addErrorMessage($msg);
            $valid = false;
        }

        // Model loading / creating
        $data['model'] = $this->getModel($data['model']);

        // Power cord type loading / creating
        $data['power'] = $this->getPowerCordType($data['power']);

        // Warehouse loading
        $warehouse = $this->getWarehouse($data['warehouse']);
        if ($warehouse === null) {
            $msg = 'Warehouse "' . $data['warehouse'] . '" not found in the database  ' ;
            $this->getType()->addErrorMessage($msg);
            $valid = false;
        }
        $data['warehouse'] = $warehouse;

        return $valid;
    }

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function storeRowData(array $data, $row)
    {

        $manager      = $this->getType()->getCrud()->getService('doctrine')->getManager();
        $eventFactory = new EventFactory($manager);

        $seedUnit = new \Elektra\SeedBundle\Entity\SeedUnits\SeedUnit();
        $seedUnit->setSerialNumber($data['serial']);
        $seedUnit->setModel($data['model']);
        $seedUnit->setPowerCordType($data['power']);
        $seedUnit->setLocation($data['warehouse']);

        // create and assign the available event
        $event = $eventFactory->createAvailable($data['warehouse']);
        $event->setSeedUnit($seedUnit);
        $seedUnit->getEvents()->add($event);
        $seedUnit->setShippingStatus($event->getUnitStatus());

        // create the "created from import" note
        $this->addCreatedNote($seedUnit,$row);
//        $note = new Note();
//        $note->setTitle('Created from import');
//        $note->setText('imported from file "' . $this->importEntity->getUploadFile()->getClientOriginalName() . '" - row: ' . $row);
//        $note->setTimestamp(time());
//        $note->setUser($this->getUser());
//        $seedUnit->getNotes()->add($note);

        $manager->persist($seedUnit);

        $this->addNote('Stored Seed Unit', 'Stored the Seed Unit "' . $data['serial'] . '"');
        $this->getType()->addSuccessMessage('Successfully stored the Seed Unit "' . $data['serial'] . '"');

        return true;
    }

    /**
     * @param string $serialNumber
     *
     * @return bool
     */
    private function isSerialNumberUsable($serialNumber)
    {

        $definition = $this->getType()->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'SeedUnit');
        $repository = $this->getType()->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
        $entity     = $repository->findOneBy(array('serialNumber' => $serialNumber));

        if ($entity === null) {
            return true;
        }

        return false;
    }

    /**
     * @param string $model
     *
     * @return Model
     */
    private function getModel($model)
    {

        $hash = md5($model);

        if (!array_key_exists($hash, $this->cache)) {
            $definition = $this->getType()->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'Model');
            $repository = $this->getType()->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
            $entity     = $repository->findOneBy(array('name' => $model));

            if ($entity === null) {
                $manager = $this->getType()->getCrud()->getService('doctrine')->getManager();

                $entity = new Model();
                $entity->setName($model);

                $manager->persist($entity);

                $this->addNote('Stored Model', 'Stored the Model "' . $model . '"');
                $this->getType()->addInfoMessage('Added a new model "' . $model . '"');
            }

            $this->cache[$hash] = $entity;
        }

        return $this->cache[$hash];
    }

    /**
     * @param string $powerCordType
     *
     * @return PowerCordType
     */
    private function getPowerCordType($powerCordType)
    {

        $hash = md5($powerCordType);

        if (!array_key_exists($hash, $this->cache)) {
            $definition = $this->getType()->getCrud()->getDefinition('Elektra', 'Seed', 'SeedUnits', 'PowerCordType');
            $repository = $this->getType()->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());
            $entity     = $repository->findOneBy(array('name' => $powerCordType));

            if ($entity === null) {
                $manager = $this->getType()->getCrud()->getService('doctrine')->getManager();

                $entity = new PowerCordType();
                $entity->setName($powerCordType);

                $manager->persist($entity);

                $this->addNote('Stored Power Cord Type', 'Stored the Power Cord Type "' . $powerCordType . '"');
                $this->getType()->addInfoMessage('Added a new power cord type "' . $powerCordType . '"');
            }

            $this->cache[$hash] = $entity;
        }

        return $this->cache[$hash];
    }

    /**
     * @param string $warehouse
     *
     * @return WarehouseLocation|null
     */
    private function getWarehouse($warehouse)
    {

        $definition = $this->getType()->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'WarehouseLocation');
        $repository = $this->getType()->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());

        // first try the locationIdentifier
        $entity = $repository->findOneBy(array('locationIdentifier' => $warehouse));

        if ($entity === null) {
            // if not found by locationIdentifier, try the shortName (Alias)
            $entity = $repository->findOneBy(array('shortName' => $warehouse));
        }

        return $entity;
    }
}