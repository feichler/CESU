<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\WarehouseLocationRepository")
 * @ORM\Table(name="locations_warehouse")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only;
 *          parent.name
 *          parent.alias -> locationIdentifier
 */
class WarehouseLocation extends AbstractPhysicalLocation
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

    // nothing

    /*************************************************************************
     * Lifecycle callbacks
     *************************************************************************/

    // none
}