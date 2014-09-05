<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Entity\EntityInterface;
use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AuditableInterface;

class AuditColumn extends Column
{

    protected $dateFormat = 'Y-m-d';

    protected $timeFormat = 'H:i:s';

    protected $timeZoneFormat = 'T (P)';

    public function __construct(Columns $columns)
    {

        parent::__construct($columns, 'audit');
        $this->setType('audit');
    }

    public function getDisplayData($entry, $rowNumber)
    {

        $return = array();

        if ($entry instanceof AuditableInterface) {
            $modified = $entry->getLastModifiedAudit();
            if ($modified != null) {
                $modifiedBy         = $modified->getUser()->getUsername();
                $return['modified'] = array(
                    'dateGMT'     => gmdate($this->dateFormat, $modified->getTimestamp()),
                    'timeGMT'     => gmdate($this->timeFormat, $modified->getTimestamp()),
                    'timeZoneGMT' => gmdate($this->timeZoneFormat, $modified->getTimestamp()),
                    'by'          => $modifiedBy,
                );
            }

            $created   = $entry->getCreationAudit();
            $createdBy = $created->getUser()->getUsername();

            $return['created'] = array(
                'dateGMT'     => gmdate($this->dateFormat, $created->getTimestamp()),
                'timeGMT'     => gmdate($this->timeFormat, $created->getTimestamp()),
                'timeZoneGMT' => gmdate($this->timeZoneFormat, $created->getTimestamp()),
                'by'          => $createdBy,
            );
        }

        return $return;
    }
}