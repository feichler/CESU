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
 * Class Customer
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\CustomerRepository")
 * @ORM\Table(name="customerCompanies")
 * @UniqueEntity(fields={ "shortName" }, message="")
 */
class Customer extends Company
{

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Partner", mappedBy="customers")
     */
    protected $partners;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();

        $this->partners = new ArrayCollection();
    }

    /**
     * Used for grouping (translation key)
     *
     * @return string
     */
    public function getCompanyType()
    {

        return 'forms.requests.request.companies.customer';
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


}