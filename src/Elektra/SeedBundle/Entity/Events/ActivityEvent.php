<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;

/**
 * Class ActivityEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
 * @ORM\Table(name="activityEvents")
 */
class ActivityEvent extends Event
{
    /**
     * @var ContactInfo
     *
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="events", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoId",referencedColumnName="contactInfoId")
     */
    protected $contactInfo;

    /**
     * @var ResponseEvent
     *
     * @ORM\ManyToOne(targetEntity="ResponseEvent", inversedBy="activityEvent", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="responseEventId",referencedColumnName="eventId")
     */
    protected $responseEvent;

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
     * @param \Elektra\SeedBundle\Entity\Events\ResponseEvent $responseEvent
     */
    public function setResponseEvent($responseEvent)
    {
        $this->responseEvent = $responseEvent;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Events\ResponseEvent
     */
    public function getResponseEvent()
    {
        return $this->responseEvent;
    }
}