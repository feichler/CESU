<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\Requests\Request;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\PartnerRepository")
 * @ORM\Table(name="companies_partner")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique:
 *      single fields only:
 *          parent.name
 */
class PartnerCompany extends Company
{

    /**
     * @var Collection Request[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Requests\Request", mappedBy="company", fetch="EXTRA_LAZY")
     */
    protected $requests;

    /**
     * @var PartnerType
     *
     * @ORM\ManyToOne(targetEntity="Elektra\SeedBundle\Entity\Companies\PartnerType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTypeId", referencedColumnName="partnerTypeId", nullable=false)
     */
    protected $partnerType;

    /**
     * @var Collection CustomerCompany[]
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Companies\CustomerCompany", mappedBy="partner",
     * fetch="EXTRA_LAZY")
     */
    protected $customers;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->requests  = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @param Collection Request[] $requests
     */
    public function setRequests($requests)
    {

        $this->requests = $requests;
    }

    /**
     * @param Request $request
     */
    public function addRequest(Request $request)
    {

        $this->getRequests()->add($request);
    }

    /**
     * @return Collection Request[]
     */
    public function getRequests()
    {

        return $this->requests;
    }

    /**
     * @param PartnerType $partnerType
     */
    public function setPartnerType($partnerType)
    {

        $this->partnerType = $partnerType;
    }

    /**
     * @return PartnerType
     */
    public function getPartnerType()
    {

        return $this->partnerType;
    }

    /**
     * @param Collection CustomerCompany[] $customers
     */
    public function setCustomers($customers)
    {

        $this->customers = $customers;
    }

    /**
     * @param CustomerCompany $customer
     */
    public function addCustomer(CustomerCompany $customer)
    {

        $this->getCustomers()->add($customer);
    }

    /**
     * @return Collection CustomerCompany[]
     */
    public function getCustomers()
    {

        return $this->customers;
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