<?php
// URGENT: not reworked yet
// TODO rework
//
//namespace Elektra\SeedBundle\Entity\Reports;
//
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\ORM\Mapping as ORM;
//use Elektra\CrudBundle\Entity\EntityInterface as CrudInterface;
//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
//use Symfony\Component\Validator\Constraints as Assert;
//use Elektra\SeedBundle\Entity\Auditing\Audit;
//use Elektra\SeedBundle\Entity\AuditableInterface;
//
///**
// * Class Models
// *
// * @package Elektra\SeedBundle\Entity\SeedUnits
// *
// * @version 0.1-dev
// *
// * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\SeedUnits\ModelRepository")
// * @ORM\Table(name="reports")
// *
// * @ORM\InheritanceType("JOINED")
// * @ORM\DiscriminatorColumn(name="reportType",type="string")
// * @ORM\DiscriminatorMap({
// *  "seedUnit" = "SeedUnit",
// * })
// */
//abstract class Report implements AuditableInterface, CrudInterface
//{
//
//    /**
//     * @var int
//     *
//     * @ORM\Id
//     * @ORM\Column(type="integer")
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    protected $reportId;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=255)
//     */
//    protected $reportPath;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=100)
//     */
//    protected $reportFile;
//
//    /**
//     * @var string
//     *
//     * @ORM\Column(type="string", length=5)
//     */
//    protected $fileType;
//
//    /**
//     * @var bool
//     *
//     * @ORM\Column(type="boolean")
//     */
//    protected $processed;
//
//    /**
//     * @var ArrayCollection
//     *
//     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY", cascade={"persist", "remove"}, orphanRemoval=true)
//     * @ORM\OrderBy({"timestamp" = "DESC"})
//     * @ORM\JoinTable(name = "reports_audits",
//     *      joinColumns = {@ORM\JoinColumn(name = "reportId", referencedColumnName = "reportId")},
//     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true, onDelete="CASCADE")}
//     * )
//     */
//    protected $audits;
//
//    public function __construct()
//    {
//
//        $this->audits = new ArrayCollection();
//    }
//
//    /**
//     * @return int
//     */
//    public function getReportId()
//    {
//
//        return $this->reportId;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getId()
//    {
//
//        return $this->getReportId();
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getTitle()
//    {
//
//        return $this->getReportFile();
//    }
//
//    /**
//     * @param string $reportFile
//     */
//    public function setReportFile($reportFile)
//    {
//
//        $this->reportFile = $reportFile;
//    }
//
//    /**
//     * @return string
//     */
//    public function getReportFile()
//    {
//
//        return $this->reportFile;
//    }
//
//    /**
//     * @param string $reportPath
//     */
//    public function setReportPath($reportPath)
//    {
//
//        $this->reportPath = $reportPath;
//    }
//
//    /**
//     * @return string
//     */
//    public function getReportPath()
//    {
//
//        return $this->reportPath;
//    }
//
//    /**
//     * @param string $fileType
//     */
//    public function setFileType($fileType)
//    {
//
//        $this->fileType = $fileType;
//    }
//
//    /**
//     * @return string
//     */
//    public function getFileType()
//    {
//
//        return $this->fileType;
//    }
//
//    /**
//     * @param boolean $processed
//     */
//    public function setProcessed($processed)
//    {
//
//        $this->processed = $processed;
//    }
//
//    /**
//     * @return boolean
//     */
//    public function getProcessed()
//    {
//
//        return $this->processed;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function setAudits($audits)
//    {
//
//        $this->audits = $audits;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getAudits()
//    {
//
//        return $this->audits;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getCreationAudit()
//    {
//
//        $audits = $this->getAudits()->slice(0, 1);
//
//        return $audits[0];
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function getLastModifiedAudit()
//    {
//
//        $audits = $this->getAudits();
//        if ($audits->count() > 1) {
//            $audits = $audits->slice($audits->count() - 1, 1);
//
//            return $audits[0];
//        }
//
//        return null;
//    }
//}