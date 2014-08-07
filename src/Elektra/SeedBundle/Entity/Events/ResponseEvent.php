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
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\ContactInfo", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoId", referencedColumnName="contactInfoId", nullable=false)
     */
    protected $contactInfo;

    /**
     * @var ActivityEvent
     *
     * @ORM\OneToOne(targetEntity="ActivityEvent", inversedBy="responseEvent", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="activityEventId", referencedColumnName="eventId", nullable=false)
     */
    protected $activityEvent;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $responseReceived = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isAcknowledged = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param ContactInfo $contactInfo
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return ContactInfo
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * @param ActivityEvent $activityEvent
     */
    public function setActivityEvent($activityEvent)
    {
        $this->activityEvent = $activityEvent;
    }

    /**
     * @return ActivityEvent
     */
    public function getActivityEvent()
    {
        return $this->activityEvent;
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