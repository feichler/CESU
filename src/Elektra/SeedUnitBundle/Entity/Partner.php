<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Partner
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="partners")
 */
class Partner extends Company
{

    /**
     * @ORM\ManyToOne(targetEntity="PartnerTier")
     * @ORM\JoinColumn(name="partnerTierId", referencedColumnName="partnerTierId")
     */
    protected $tier;
}