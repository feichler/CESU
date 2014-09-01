<?php

namespace Elektra\SeedBundle\Entity\Imports;

use Doctrine\Common\Collections\ArrayCollection;
use Elektra\SeedBundle\Entity\AuditableInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;

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