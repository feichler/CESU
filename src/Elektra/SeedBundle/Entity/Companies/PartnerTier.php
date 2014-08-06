<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PartnerTier
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="partnerTiers")
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

    public function __construct()
    {
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