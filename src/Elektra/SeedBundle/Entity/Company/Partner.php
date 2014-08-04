<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Partner
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="companies_partner")
 */
class Partner extends Company
{

    /**
     * @var PartnerTier
     *
     * @ORM\ManyToOne(targetEntity="PartnerTier", inversedBy="partners", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTierId",referencedColumnName="partnerTierId")
     */
    protected $partnerTier;

    public function __construct()
    {

        parent::__construct();
    }
}