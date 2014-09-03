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

/**
 * Class StatusEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="statusEvents")
 */
abstract class StatusEvent extends Event
{

    /**
     * @var UnitStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitStatusId", referencedColumnName="unitStatusId", nullable=false)
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