<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EventType
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
 * @ORM\Table(name="eventTypes")
 */
class EventType
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $eventTypeId;

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
        return $this->eventTypeId;
    }

    /**
     * @return int
     */
    public function getEventTypeId()
    {
        return $this->eventTypeId;
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