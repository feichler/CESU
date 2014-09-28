<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Imports\Import;
use Elektra\SeedBundle\Import\ImportException;
use Elektra\SeedBundle\Import\Type;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImportController extends Controller
{

    /**
     * @return Definition
     */
    protected function getDefinition()
    {

        return $this->get('navigator')->getDefinition('Elektra', 'Seed', 'Imports', 'Import');
    }

    public function beforeAddEntity(EntityInterface $entity, FormInterface $form = null)
    {

        if (!$entity instanceof Import) {
            $this->addErrorMessage('Can only process import entities');

            return false;
        }

        // find the appropriate type instance to handle the uploaded file
        $types    = Type::getKnownTypes();
        $instance = null;
        try {
            foreach ($types as $type) {
                $typeInstance = new $type($this->getCrud());
                if ($typeInstance->canHandle($entity)) {
                    $instance = $typeInstance;
                    break;
                }
            }
        } catch (ImportException $exception) {
            $this->addErrorMessage($exception->getMessage());

            // instance's canHandle has thrown an error, meaning the file is either not supported or invalid
            return false;
        }

        // check if an instance has been found to handle the upload
        if ($instance === null) {
            $this->addErrorMessage('Cannot find a processor for the uploaded file');

            return false;
        }

        // if this point is reached, the processor is already set up and ready to execute
        $processed = $instance->getProcessor()->execute();

        // move the file into the archive and set the remaining values on the import entity
        $archive  = dirname($this->get('kernel')->getRootDir()) . '/var/imports/';
        $filename = $instance->getIdentifier() . '_' . time() . '.' . $entity->getUploadFile()->getClientOriginalExtension();

        try {
            $entity->getUploadFile()->move($archive, $filename);
        } catch (FileException $exception) {
            $this->addErrorMessage($exception->getMessage());

            return false;
        }

        // set the remaining values
        $entity->setImportType($instance->getTitle());
        $entity->setOriginalFileName($entity->getUploadFile()->getClientOriginalName());
        $entity->setServerFileName($filename);

        return $processed;
    }
}