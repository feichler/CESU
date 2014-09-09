<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

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
 * @ORM\Table(name="partnerCompanies")
 * @UniqueEntity(fields={ "shortName" }, message="")
 */
class Partner extends RequestingCompany
{

    /**
     * @var PartnerTier
     *
     * @ORM\ManyToOne(targetEntity="PartnerTier", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="partnerTierId", referencedColumnName="partnerTierId")
     */
    protected $partnerTier;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $unitsLimit;

    /**
     *
     */
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
}