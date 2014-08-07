<?php

namespace Elektra\SeedBundle\Entity\Requests;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RequestStatus
 *
 * @package Elektra\SeedBundle\Entity\Requests
 *
 * @ORM\Entity
 * @ORM\Table(name="requestStatuses")
 */
class RequestStatus
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $requestStatusId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
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
        return $this->requestStatusId;
    }

    /**
     * @return int
     */
    public function getRequestStatusId()
    {
        return $this->requestStatusId;
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