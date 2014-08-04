<?php

namespace Elektra\SeedBundle\Entity\Company;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Company
 *
 * @package Elektra\SeedBundle\Entity\Company
 *
 * @ORM\Entity
 * @ORM\Table(name="companies")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="companyType",type="string")
 * @ORM\DiscriminatorMap({
 *  "partner" = "Partner",
 *  "customer" = "Customer"
 * })
 */
abstract class Company
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $companyId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $shortName;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {

        return $this->companyId;
    }

    /**
     * @return int
     */
    public function getCompanyId()
    {

        return $this->companyId;
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
     * @param string $shortName
     */
    public function setShortName($shortName)
    {

        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {

        return $this->shortName;
    }
}