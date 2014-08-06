<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContactInfoType
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="contactInfoTypes")
 */
class ContactInfoType
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $contactInfoTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->contactInfoTypeId;
    }

    /**
     * @return int
     */
    public function getContactInfoTypeId()
    {
        return $this->contactInfoTypeId;
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
}