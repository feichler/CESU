<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Partner
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\PartnerRepository")
 * @ORM\Table(name="companies_partner")
 * @UniqueEntity(fields={ "shortName" }, message="")
 */
class Partner extends Company
{

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Requests\Request", mappedBy="company", fetch="EXTRA_LAZY")
     */
    protected $requests;

    /**
     * @var PartnerTier
     *
     * @ORM\ManyToOne(targetEntity="PartnerTier", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTierId", referencedColumnName="partnerTierId")
     */
    protected $partnerTier;

    /**
     * @var PartnerType
     *
     * @ORM\ManyToOne(targetEntity="PartnerType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTypeId", referencedColumnName="partnerTypeId", nullable=false)
     */
    protected $partnerType;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $unitsLimit;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Customer", mappedBy="partners")
     */
    protected $customers;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->requests  = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    /**
     * @param PartnerTier $partnerTier
     */
    public function setPartnerTier($partnerTier)
    {

        $this->partnerTier = $partnerTier;
    }

    /**
     * @return PartnerTier
     */
    public function getPartnerTier()
    {

        return $this->partnerTier;
    }

    /**
     * @param int $unitsLimit
     */
    public function setUnitsLimit($unitsLimit)
    {

        $this->unitsLimit = $unitsLimit;
    }

    /**
     * @return int
     */
    public function getUnitsLimit()
    {

        return $this->unitsLimit;
    }

    /**
     * Used for grouping (translation key)
     *
     * @return string
     */
    public function getCompanyType()
    {

        return 'forms.requests.request.companies.partner';
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $requests
     */
    public function setRequests($requests)
    {

        $this->requests = $requests;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRequests()
    {

        return $this->requests;
    }

    /**
     * @param PartnerTier $partnerType
     */
    public function setPartnerType($partnerType)
    {

        $this->partnerType = $partnerType;
    }

    /**
     * @return PartnerTier
     */
    public function getPartnerType()
    {

        return $this->partnerType;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $customers
     */
    public function setCustomers($customers)
    {

        $this->customers = $customers;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCustomers()
    {

        return $this->customers;
    }


}