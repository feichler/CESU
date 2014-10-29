<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Helper;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\StatusUsageRepository")
 * @ORM\Table(name="statuses_usage")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      already defined by parent
 */
class StatusUsage extends Status
{

    // TODO remove old code after verification
    //    const USAGE_IDLE = "idle";
    //
    //    const LOCATION_SCOPE_PARTNER  = 'P';
    //    const LOCATION_SCOPE_CUSTOMER = 'C';
    //
    //    const LOCATION_CONSTRAINT_REQUIRED = 'R';
    //    const LOCATION_CONSTRAINT_OPTIONAL = 'O';
    //    const LOCATION_CONSTRAINT_HIDDEN   = 'H';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $locationConstraint;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false)
     */
    protected $locationScope;

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
     * @param string $locationConstraint
     *
     * @throws \OutOfBoundsException
     */
    public function setLocationConstraint($locationConstraint)
    {

        if (!in_array($locationConstraint, Helper::getStatusUsageLocationConstraints())) {
            throw new \OutOfBoundsException("Unknown location constraint value: " . $locationConstraint);
        }

        // TODO remove old code after verification
        //        if (!in_array($locationConstraint, array(
        //
        //            StatusUsage::LOCATION_CONSTRAINT_HIDDEN,
        //            StatusUsage::LOCATION_CONSTRAINT_OPTIONAL,
        //            StatusUsage::LOCATION_CONSTRAINT_REQUIRED
        //        ))
        //        ) {
        //            throw new \OutOfBoundsException("Unknown location constraint value: " . $locationConstraint);
        //        }

        $this->locationConstraint = $locationConstraint;
    }

    /**
     * @return string
     */
    public function getLocationConstraint()
    {

        return $this->locationConstraint;
    }

    /**
     * @param $locationScope
     *
     * @throws \OutOfBoundsException
     */
    public function setLocationScope($locationScope)
    {

        if (!in_array($locationScope, Helper::getStatusUsageLocationScopes())) {
            throw new \OutOfBoundsException("Unknown location scope value: " . $locationScope);
        }

        // TODO remove old code after verification
        //        if (!in_array($locationScope, array(StatusUsage::LOCATION_SCOPE_CUSTOMER, StatusUsage::LOCATION_SCOPE_PARTNER))
        //        ) {
        //            throw new \OutOfBoundsException("Unknown location scope value: " . $locationScope);
        //        }

        $this->locationScope = $locationScope;
    }

    /**
     * @return string
     */
    public function getLocationScope()
    {

        return $this->locationScope;
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