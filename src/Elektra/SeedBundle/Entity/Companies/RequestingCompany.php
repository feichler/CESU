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

/**
 * Class RequestingCompany
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\RequestingCompanyRepository")
 * @ORM\Table(name="requestingCompanies")
 */
abstract class RequestingCompany extends Company
{
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elektra\SeedBundle\Entity\Requests\Request", mappedBy="company", fetch="EXTRA_LAZY")
     */
    protected $requests;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
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
}