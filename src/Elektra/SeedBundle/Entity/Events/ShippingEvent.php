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
 * Class ShippingEvent
 *
 * @package Elektra\SeedBundle\Entity\Events
 *
 * @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="shippingEvents")
 */
class ShippingEvent extends Event
{

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $shippingNumber;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
    }

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
}