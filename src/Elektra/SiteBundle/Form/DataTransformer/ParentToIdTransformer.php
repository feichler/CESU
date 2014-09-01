<?php

namespace Elektra\SiteBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ParentToIdTransformer implements DataTransformerInterface
{

    /**
     * @var ObjectManager
     */
    private $om;

    private $repository;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {

        $this->om = $om;
    }

    public function transform($value)
    {

        if (is_null($value) || !is_object($value) || !method_exists($value, 'getId')) {
            echo 'A';
            return '';
        }
echo 'B'.$value->getId();
        return $value->getId();
    }

    public function reverseTransform($value)
    {

        if (!$value) {
            echo 'C';
            return null;
        }

        $entity = $this->om->getRepository($this->repository)->find($value);

        if (null === $entity) {
            echo 'D';
            throw new TransformationFailedException('An entity with id ' . $value . ' does not exist');
        }

        echo 'E'.$entity->getId();
        return $entity;
    }

    /**
     * @param mixed $repository
     */
    public function setRepository($repository)
    {

        $this->repository = $repository;
    }
}