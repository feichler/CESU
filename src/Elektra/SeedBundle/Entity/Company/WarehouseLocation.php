<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class WarehouseLocation
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="location_warehouses")
 */
class WarehouseLocation extends Location
{

    public function __construct()
    {

        parent::__construct();
    }
}