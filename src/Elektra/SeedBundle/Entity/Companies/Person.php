<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Person
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table("persons")
 */
class Person
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $personId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $academicTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $jobTitle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $salutation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ContactInfo", mappedBy="person", fetch="EXTRA_LAZY", cascade={"remove"})
     */
    protected $contactInfo;

    public function __construct()
    {
        $this->contactInfo = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->personId;
    }

    /**
     * @return int
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param ArrayCollection $contactInfo
     */
    public function setContactInfo($contactInfo)
    {
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return ArrayCollection
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * @param string $academicTitle
     */
    public function setAcademicTitle($academicTitle)
    {
        $this->academicTitle = $academicTitle;
    }

    /**
     * @return string
     */
    public function getAcademicTitle()
    {
        return $this->academicTitle;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $salutation
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }
}