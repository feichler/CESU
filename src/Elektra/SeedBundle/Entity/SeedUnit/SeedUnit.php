<?php

namespace Elektra\SeedBundle\Entity\SeedUnit;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SeedUnit
 *
 * @package Elektra\SeedBundle\Entity\SeedUnit
 *
 * @ORM\Entity
 * @ORM\Table(name="seedunit")
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
     * @var Model
     *
     * @ORM\ManyToOne(targetEntity="Model", inversedBy="seedUnits", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="modelId", referencedColumnName="modelId")
     */
    protected $model;

    /**
     * @var PowerType
     *
     * @ORM\ManyToOne(targetEntity="PowerType", inversedBy="seedUnits", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="powerTypeId", referencedColumnName="powerTypeId")
     */
    protected $powerType;

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
     * @param Model $model
     */
    public function setModel($model)
    {

        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param PowerType $powerType
     */
    public function setPowerType($powerType)
    {

        $this->powerType = $powerType;
    }

    /**
     * @return PowerType
     */
    public function getPowerType()
    {

        return $this->powerType;
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