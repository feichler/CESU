<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;

/**
 * Class ResponseEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
 * @ORM\Table(name="responseEvents")
 */
class ResponseEvent extends Event
{
    /**
     * @var ContactInfo
     *
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="events", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoId",referencedColumnName="contactInfoId")
     */
    protected $contactInfo;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $responseReceived;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isAcknowledged;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\ContactInfo $contactInfo
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\ContactInfo
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * @param boolean $isAcknowledged
     */
    public function setIsAcknowledged($isAcknowledged)
    {
        $this->isAcknowledged = $isAcknowledged;
    }

    /**
     * @return boolean
     */
    public function getIsAcknowledged()
    {
        return $this->isAcknowledged;
    }

    /**
     * @param boolean $responseReceived
     */
    public function setResponseReceived($responseReceived)
    {
        $this->responseReceived = $responseReceived;
    }

    /**
     * @return boolean
     */
    public function getResponseReceived()
    {
        return $this->responseReceived;
    }
}