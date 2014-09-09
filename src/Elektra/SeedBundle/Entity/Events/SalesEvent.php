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
 * Class SalesEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="salesEvents")
 */
class SalesEvent extends Event
{
    /**
     * @var UnitSalesStatus
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Events\UnitSalesStatus", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="unitSalesStatusId", referencedColumnName="unitSalesStatusId", nullable=false)
     */
    protected $salesStatus;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * @param UnitSalesStatus $salesStatus
     */
    public function setSalesStatus($salesStatus)
    {
        $this->salesStatus = $salesStatus;
    }

    /**
     * @return UnitSalesStatus
     */
    public function getSalesStatus()
    {
        return $this->salesStatus;
    }
}