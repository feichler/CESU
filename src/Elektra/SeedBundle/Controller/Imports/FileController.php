<?php

namespace Elektra\SeedBundle\Controller\Imports;

use Elektra\CrudBundle\Controller\Controller;
use Elektra\SeedBundle\Entity\EntityInterface;
use Elektra\SeedBundle\Entity\Imports\File;

abstract class FileController extends Controller
{

    protected function getUploadDirectory()
    {

        $directory = dirname($this->get('kernel')->getRootDir());
        $directory .= '/var/uploads/' . $this->getCrud()->getDefinition()->getName();

        return $directory;
    }

    public function beforeAddEntity(EntityInterface $entity)
    {

        if ($entity instanceof File) {
            $file      = $entity->getFile();
            $uploadDir = $this->getUploadDirectory();
            $entity->setOriginalFile($file->getClientOriginalName());
            $entity->setUploadPath($uploadDir);
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $entity->setUploadFile($filename);
            $entity->setFileType($file->getClientOriginalExtension());
            $file->move($uploadDir, $filename);
        }

        return true;
    }

    public function afterAddEntity(EntityInterface $entity)
    {

        if ($entity instanceof File) {
            $path = $entity->getUploadPath() . '/' . $entity->getUploadFile();

            $result = $this->processUploadedFile($entity, $path);

            if ($result) {
                $entity->setProcessed(true);
            }
        }
    }

    protected abstract function processUploadedFile(File $fileEntity, $path);
}
