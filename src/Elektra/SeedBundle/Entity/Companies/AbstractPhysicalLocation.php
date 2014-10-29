<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="locations_physical")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: defined by inheriting classes (parent.name within inheriting)
 */
abstract class AbstractPhysicalLocation extends AbstractLocation
{

    /**
     * @var Address
     *
     * @ORM\OneToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\Address", fetch="EXTRA_LAZY",
     *                                                                           cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="addressId", referencedColumnName="addressId", nullable=false)
     */
    protected $address;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @param Address $address
     */
    public function setAddress($address)
    {

        $this->address = $address;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {

        return $this->address;
    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // nothing

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}