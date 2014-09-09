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
use Elektra\SeedBundle\Entity\Companies\Location;

/**
 * Class PartnerEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="partnerEvents")
 */
class PartnerEvent extends Event
{

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Location", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="locationId", referencedColumnName="locationId")
     */
    protected $location;

    /**
     * @var UnitUsage
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitUsage", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitUsageId", referencedColumnName="unitUsageId")
     */
    protected $usage;

    /**
     *
     */
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

    /**
     * @param UnitUsage $usage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
    }

    /**
     * @return UnitUsage
     */
    public function getUsage()
    {
        return $this->usage;
    }
}