<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UnitStatus
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
 * @ORM\Table(name="unitStatuses")
 */
class UnitStatus
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $unitStatusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->unitStatusId;
    }

    /**
     * @return int
     */
    public function getUnitStatusId()
    {
        return $this->unitStatusId;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}