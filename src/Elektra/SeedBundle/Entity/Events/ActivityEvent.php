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
class ActivityEvent extends StatusEvent
{
    /**
     * @var ContactInfo
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\ContactInfo", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoId", referencedColumnName="contactInfoId", nullable=false)
     */
    protected $contactInfo;

    /**
     * @var ResponseEvent
     *
     * @ORM\OneToOne(targetEntity="ResponseEvent", mappedBy="activityEvent", fetch="EXTRA_LAZY")
     */
    protected $responseEvent;

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
     * @param ResponseEvent $responseEvent
     */
    public function setResponseEvent($responseEvent)
    {
        $this->responseEvent = $responseEvent;
    }

    /**
     * @return ResponseEvent
     */
    public function getResponseEvent()
    {
        return $this->responseEvent;
    }
}