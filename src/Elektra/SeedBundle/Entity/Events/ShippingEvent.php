<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Companies\Location;

/**
 * Class ShippingEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @ORM\Entity
 * @ORM\Table(name="shippingEvents")
 */
class ShippingEvent extends Event
{
    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId", nullable=false)
     */
    protected $location;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Location $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }
}