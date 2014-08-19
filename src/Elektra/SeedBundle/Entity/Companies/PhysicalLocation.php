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
 * Class PhysicalLocation
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repositories\Companies\PhysicalLocationRepository")
 * @ORM\Table(name="physicalLocations")
 */
abstract class PhysicalLocation extends Location
{
    /*
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Address", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="testAddressId", referencedColumnName="addressId", nullable=false)
     */
    protected $address;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
        //$this->address = new Address();
    }

    /**
     * @param \Elektra\SeedBundle\Entity\Companies\Address $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \Elektra\SeedBundle\Entity\Companies\Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}