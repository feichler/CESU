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
 * @ORM\Table(name="imports_file")
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="importType",type="string")
 * @ORM\DiscriminatorMap({
 *  "seedUnit" = "SeedUnit",
 * })
 */
abstract class File implements AuditableInterface, CrudInterface
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $fileId;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $originalFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $uploadPath;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $uploadFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5)
     */
    protected $fileType;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $processed;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "imports_file_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "fileId", referencedColumnName = "fileId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
     * )
     */
    protected $audits;

    public function __construct()
    {

        $this->audits    = new ArrayCollection();
        $this->processed = false;
    }

    /**
     * @return int
     */
    public function getFileId()
    {

        return $this->fileId;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getFileId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {

        return $this->getOriginalFile();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile($file)
    {

        $this->file = $file;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {

        return $this->file;
    }

    /**
     * @param string $fileName
     */
    public function setOriginalFile($fileName)
    {

        $this->originalFile = $fileName;
    }

    /**
     * @return string
     */
    public function getOriginalFile()
    {

        return $this->originalFile;
    }

    /**
     * @param string $filePath
     */
    public function setUploadPath($filePath)
    {

        $this->uploadPath = $filePath;
    }

    /**
     * @return string
     */
    public function getUploadPath()
    {

        return $this->uploadPath;
    }

    /**
     * @param string $uploadFile
     */
    public function setUploadFile($uploadFile)
    {

        $this->uploadFile = $uploadFile;
    }

    /**
     * @return string
     */
    public function getUploadFile()
    {

        return $this->uploadFile;
    }

    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {

        $this->fileType = $fileType;
    }

    /**
     * @return string
     */
    public function getFileType()
    {

        return $this->fileType;
    }



    /**
     * @param boolean $processed
     */
    public function setProcessed($processed)
    {

        $this->processed = $processed;
    }

    /**
     * @return boolean
     */
    public function getProcessed()
    {

        return $this->processed;
    }

    /**
     * {@inheritdoc}
     */
    public function setAudits($audits)
    {

        $this->audits = $audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getAudits()
    {

        return $this->audits;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreationAudit()
    {
        return \Elektra\SeedBundle\Auditing\Helper::getFirstAudit($this->getAudits());

    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedAudit()
    {
        return \Elektra\SeedBundle\Auditing\Helper::getLastAudit($this->getAudits());

    }
}