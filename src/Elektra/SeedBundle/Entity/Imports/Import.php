<?php
// URGENT: rework not completed
// TODO rework

namespace Elektra\SeedBundle\Entity\Imports;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Elektra\SeedBundle\Entity\AbstractAuditableAnnotableEntity;
use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\SeedBundle\Entity\Notes\Note;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Imports\ImportRepository")
 * @ORM\Table(name="imports")
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * Unique: nothing
 */
class Import extends AbstractAuditableAnnotableEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned" = true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $importId;

    /**
     * @var UploadedFile
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $uploadFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $originalFileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $serverFileName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $importType;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    protected $numberOfEntries;

    /**
     * @var Collection Note[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Notes\Note", fetch="EXTRA_LAZY", cascade={"persist",
     *                              "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "imports_notes",
     *      joinColumns = {@ORM\JoinColumn(name = "importId", referencedColumnName = "importId", onDelete="CASCADE")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "noteId", referencedColumnName = "noteId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $notes;

    /**
     * @var Collection Audit[]
     *
     * @ORM\ManyToMany(targetEntity = "Elektra\SeedBundle\Entity\Auditing\Audit", fetch="EXTRA_LAZY",
     *                              cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"timestamp" = "DESC"})
     * @ORM\JoinTable(name = "imports_audits",
     *      joinColumns = {@ORM\JoinColumn(name = "importId", referencedColumnName = "importId")},
     *      inverseJoinColumns = {@ORM\JoinColumn(name = "auditId", referencedColumnName = "auditId", unique = true,
     *      onDelete="CASCADE")}
     * )
     */
    protected $audits;

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->numberOfEntries = 0;
    }

    /*************************************************************************
     * Getters / Setters
     *************************************************************************/

    /**
     * @return int
     */
    public function getImportId()
    {

        return $this->importId;
    }

    /**
     * @param string $originalFileName
     */
    public function setOriginalFileName($originalFileName)
    {

        $this->originalFileName = $originalFileName;
    }

    /**
     * @return string
     */
    public function getOriginalFileName()
    {

        return $this->originalFileName;
    }

    /**
     * @param string $serverFileName
     */
    public function setServerFileName($serverFileName)
    {

        $this->serverFileName = $serverFileName;
    }

    /**
     * @return string
     */
    public function getServerFileName()
    {

        return $this->serverFileName;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadFile
     */
    public function setUploadFile($uploadFile)
    {

        $this->uploadFile = $uploadFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getUploadFile()
    {

        return $this->uploadFile;
    }

    /**
     * @param string $importType
     */
    public function setImportType($importType)
    {

        $this->importType = $importType;
    }

    /**
     * @return string
     */
    public function getImportType()
    {

        return $this->importType;
    }

    /**
     * @param string $numberOfEntries
     */
    public function setNumberOfEntries($numberOfEntries)
    {

        $this->numberOfEntries = $numberOfEntries;
    }

    public function incrementNumberOfEntries()
    {

        $this->numberOfEntries++;
    }

    /**
     * @return string
     */
    public function getNumberOfEntries()
    {

        return $this->numberOfEntries;
    }

    //    /**
    //     * @param string $numberOfEntriesProcessed
    //     */
    //    public function setNumberOfEntriesProcessed($numberOfEntriesProcessed)
    //    {
    //
    //        $this->numberOfEntriesProcessed = $numberOfEntriesProcessed;
    //    }
    //
    //    public function incrementNumberOfEntriesProcessed()
    //    {
    //
    //        $this->numberOfEntriesProcessed++;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function getNumberOfEntriesProcessed()
    //    {
    //
    //        return $this->numberOfEntriesProcessed;
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function setNotes($notes)
    //    {
    //
    //        $this->notes = $notes;
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getNotes()
    //    {
    //
    //        return $this->notes;
    //    }
    //
    //    public function addNote($title, $text, $user)
    //    {
    //
    //        $note = new Note();
    //        $note->setTitle($title);
    //        $note->setText($text);
    //        $note->setTimestamp(time());
    //        $note->setUser($user);
    //
    //        $this->getNotes()->add($note);
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
    //        return \Elektra\SeedBundle\Auditing\Helper::getFirstAudit($this->getAudits());
    //    }
    //
    //    /**
    //     * {@inheritdoc}
    //     */
    //    public function getLastModifiedAudit()
    //    {
    //
    //        return \Elektra\SeedBundle\Auditing\Helper::getLastAudit($this->getAudits());
    //    }

    /*************************************************************************
     * EntityInterface
     *************************************************************************/

    /**
     * {@inheritdoc}
     */
    public function getId()
    {

        return $this->getImportId();
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {

        return $this->getOriginalFileName();
    }



    //    /**
    //     * Return the representative title of the entity
    //     *
    //     * @return string
    //     */
    //    public function getTitle()
    //    {
    //
    //        return $this->importType . ' - ' . $this->getOriginalFileName();
    //    }
}