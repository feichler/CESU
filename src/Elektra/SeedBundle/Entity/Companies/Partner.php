<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Partner
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="partnerCompanies")
 */
class Partner extends Company
{
    /**
     * @var PartnerTier
     *
     * @ORM\ManyToOne(targetEntity="PartnerTier", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTierId", referencedColumnName="partnerTierId", nullable=false)
     */
    protected $partnerTier;

    public function __construct()
    {
        parent::__construct();
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
}