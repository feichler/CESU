<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContactInfo
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="contactInfo")
 */
class ContactInfo
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $contactInfoId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $text;

    /**
     * @var ContactInfoType
     *
     * @ORM\ManyToOne(targetEntity="ContactInfoType", inversedBy="contactInfo", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="contactInfoTypeId",referencedColumnName="contactInfoTypeId")
     */
    protected $contactInfoType;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->contactInfoId;
    }

    /**
     * @return int
     */
    public function getContactInfoId()
    {
        return $this->contactInfoId;
    }

    /**
     * @param ContactInfoType $contactInfoType
     */
    public function setContactInfoType($contactInfoType)
    {
        $this->contactInfoType = $contactInfoType;
    }

    /**
     * @return ContactInfoType
     */
    public function getContactInfoType()
    {
        return $this->contactInfoType;
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
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}