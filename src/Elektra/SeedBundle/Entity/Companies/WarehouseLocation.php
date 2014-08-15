<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class WarehouseLocation
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="warehouseLocations")
 */
class WarehouseLocation extends Location
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20, unique=true)
     */
    protected $locationIdentifier;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $locationIdentifier
     */
    public function setLocationIdentifier($locationIdentifier)
    {
        $this->locationIdentifier = $locationIdentifier;
    }

    /**
     * @return string
     */
    public function getLocationIdentifier()
    {
        return $this->locationIdentifier;
    }
}