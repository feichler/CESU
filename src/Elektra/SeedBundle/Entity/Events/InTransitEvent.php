<?php

namespace Elektra\SeedBundle\Entity\Events;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Event\InTransitRepository")
 * @ORM\Table(name="events_intransit")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing?
 */
class InTransitEvent extends Event
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $shippingNumber;

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
     * @param string $shippingNumber
     */
    public function setShippingNumber($shippingNumber)
    {

        $this->shippingNumber = $shippingNumber;
    }

    /**
     * @return string
     */
    public function getShippingNumber()
    {

        return $this->shippingNumber;
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