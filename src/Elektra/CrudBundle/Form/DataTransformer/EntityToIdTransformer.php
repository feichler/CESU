<?php

namespace Elektra\CrudBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $mgr;

    private $entityClass;

    public function __construct(ObjectManager $mgr, $entityClass = null)
    {
        $this->mgr = $mgr;
        $this->entityClass = $entityClass;
    }

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        return $value != null ? $value->getId() : "";
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        if (!$value)
        {
            return null;
        }

        $entity = $this->mgr->getRepository($this->entityClass)->find($value);

        if ($entity === null)
        {
            throw new TransformationFailedException('An entity of class ' . $this->entityClass . ' with id "'. $value .'" does not exist!');
        }

        return $entity;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($class)
    {
        $this->entityClass = $class;
    }
}