<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PartnerTier
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="companies_partner_tier")
 */
class PartnerTier
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $partnerTierId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $unitsLimit;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Partner", mappedBy="partnerTier", fetch="EXTRA_LAZY")
     */
    protected $partners;

    public function __construct()
    {

        $this->partners = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->partnerTierId;
    }

    /**
     * @return int
     */
    public function getPartnerTierId()
    {

        return $this->partnerTierId;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $partners
     */
    public function setPartners($partners)
    {

        $this->partners = $partners;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPartners()
    {

        return $this->partners;
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
}