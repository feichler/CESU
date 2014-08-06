<?php

namespace Elektra\SeedBundle\Entity\SeedUnits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeedUnits
 *
 * @package Elektra\SeedBundle\Entity\SeedUnits
 *
 * @ORM\Entity
 * @ORM\Table(name="seedUnits")
 */
class SeedUnit
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $seedUnitId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75, unique=true)
     */
    protected $serialNumber;

    /**
     * @var SeedUnitModel
     *
     * @ORM\ManyToOne(targetEntity="SeedUnitModel", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="seedUnitModelId", referencedColumnName="seedUnitModelId")
     */
    protected $model;

    /**
     * @var PowerCordType
     *
     * @ORM\ManyToOne(targetEntity="PowerCordType", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="powerCordTypeId", referencedColumnName="powerCordTypeId")
     */
    protected $powerCordType;

    function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->seedUnitId;
    }

    /**
     * @return int
     */
    public function getSeedUnitId()
    {
        return $this->seedUnitId;
    }

    /**
     * @param SeedUnitModel $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return SeedUnitModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param PowerCordType $powerCordType
     */
    public function setPowerCordType($powerCordType)
    {
        $this->powerCordType = $powerCordType;
    }

    /**
     * @return PowerCordType
     */
    public function getPowerCordType()
    {
        return $this->powerCordType;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }
}