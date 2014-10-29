<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\StatusSalesRepository")
 * @ORM\Table(name="statuses_sales")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      already defined by parent
 */
class StatusSales extends Status
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