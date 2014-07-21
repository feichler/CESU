<?php

namespace Elektra\SeedUnitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PartnerTier
 *
 * @package Elektra\SeedUnitBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="partner_tiers")
 */
class PartnerTier
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $partnerTierId;


}