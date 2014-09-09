<?php

namespace Elektra\SeedBundle\Entity\Imports;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SeedUnit
 *
 * @package Elektra\SeedBundle\Entity\Imports
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Imports\SeedUnitRepository")
 * @ORM\Table(name="imports_seedunit")
 *
 */
class SeedUnit extends File
{

}