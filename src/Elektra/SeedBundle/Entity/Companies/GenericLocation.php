<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\GenericLocationRepository")
 * @ORM\Table(name="locations_generic")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only;
 *          parent.name
 *          parent.alias -> internalName
 */
class GenericLocation extends AbstractLocation
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