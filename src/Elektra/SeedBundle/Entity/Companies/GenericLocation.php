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

    const IN_TRANSIT = "inTransit";
    const UNKNOWN    = "unknown";

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=25, unique=true)
     */
    protected $alias;

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
     * @param string $internalName
     */
    public function setAlias($internalName)
    {

        $this->alias = $internalName;
    }

    /**
     * @return string
     */
    public function getAlias()
    {

        return $this->alias;
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