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

    //    // TODO update to single partner relation
    //    /**
    //     * @var Collection Partner[]
    //     *
    //     * @ORM\ManyToMany(targetEntity="Partner", inversedBy="customers")
    //     * @ORM\JoinTable(name="companies_customer_partner_map",
    //     *      joinColumns={@ORM\JoinColumn(name="customerId", referencedColumnName="companyId")},
    //     *      inverseJoinColumns={@ORM\JoinColumn(name="partnerId", referencedColumnName="companyId")}
    //     * )
    //     */
    //    protected $partners;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->partners = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    //    /**
    //     * @param Collection PartnerCompany[] $partners
    //     */
    //    public function setPartners($partners)
    //    {
    //
    //        $this->partners = $partners;
    //    }
    //
    //    /**
    //     * @param PartnerCompany $partner
    //     *
    //     */
    //    public function addPartner(PartnerCompany $partner)
    //    {
    //
    //        $this->getPartners()->add($partner);
    //    }
    //
    //    /**
    //     * @return Collection PartnerCompany[]
    //     */
    //    public function getPartners()
    //    {
    //
    //        return $this->partners;
    //    }

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