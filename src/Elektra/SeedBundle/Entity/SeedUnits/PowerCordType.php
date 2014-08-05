<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PowerType
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity
 * @ORM\Table(name="powerCordTypes")
 */
class PowerCordType
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $powerCordTypeId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $name;

    /**
     * @var string
     *

     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SeedUnits", mappedBy="powerCordType", fetch="EXTRA_LAZY")
     */
    protected $seedUnits;

    public function __construct()
    {
        $this->seedUnits = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->powerCordTypeId;
    }

    /**
     * @return int
     */
    public function getPowerCordTypeId()
    {
        return $this->powerCordTypeId;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @return ArrayCollection
     */
    public function getSeedUnits()
    {
        return $this->seedUnits;
    }

    /**
     * @return bool
     */
    public function getCanDelete() {

        return count($this->seedUnits) == 0;
    }
}