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
 * @ORM\Table(name="statusEvents")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="eventType",type="string")
 * @ORM\DiscriminatorMap({
 *  "shipping" = "ShippingEvent",
 *  "partner" = "PartnerEvent",
 *  "activity" = "ActivityEvent",
 *  "response" = "ResponseEvent",
 * })
 */
abstract class StatusEvent extends Event
{
    /**
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitStatusId", referencedColumnName="unitStatusId")
     */
    protected $unitStatus;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param UnitStatus $unitStatus
     */
    public function setUnitStatus($unitStatus)
    {
        $this->unitStatus = $unitStatus;
    }

    /**
     * @return UnitStatus
     */
    public function getUnitStatus()
    {
        return $this->unitStatus;
    }
}