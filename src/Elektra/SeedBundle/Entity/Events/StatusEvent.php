<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;

/**
 * Class ActivityEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="statusEvents")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
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

    /**
     *
     */
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