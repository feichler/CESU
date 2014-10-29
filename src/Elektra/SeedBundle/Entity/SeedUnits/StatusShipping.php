<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\StatusShippingRepository")
 * @ORM\Table(name="statuses_shipping")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      already defined by parent
 */
class StatusShipping extends Status
{

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

    // none

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    // none

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none

}