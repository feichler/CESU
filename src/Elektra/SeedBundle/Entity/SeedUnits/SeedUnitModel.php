<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Model
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity
 * @ORM\Table(name="seedUnitModels")
 */
class SeedUnitModel
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $seedUnitModelId;

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

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->seedUnitModelId;
    }

    /**
     * @return int
     */
    public function getSeedUnitModelId()
    {
        return $this->seedUnitModelId;
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
     * @return bool
     */
    public function getCanDelete() {

        return count($this->seedUnits) == 0;
    }
}