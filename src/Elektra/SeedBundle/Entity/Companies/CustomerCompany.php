<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CustomerRepository")
 * @ORM\Table(name="companies_customer")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          parent.name
 */
class CustomerCompany extends Company
{

    /**
     * @var PartnerCompany
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\PartnerCompany", inversedBy="customers",
     * fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerId", referencedColumnName="companyId")
     */
    protected $partner;

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
     * @return PartnerCompany
     */
    public function getPartner()
    {

        return $this->partner;
    }

    /**
     * @param PartnerCompany $partner
     */
    public function setPartner($partner)
    {

        $this->partner = $partner;
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