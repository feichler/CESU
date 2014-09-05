<?php

namespace Elektra\CrudBundle\Table\Column;

use Elektra\CrudBundle\Entity\EntityInterface;
use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\AuditableInterface;

class AuditColumn extends Column
{

    protected $dateFormat = 'Y-m-d H:i:s T (P)';

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
                $modifiedDate       = gmdate($this->dateFormat, $modified->getTimestamp());
                $modifiedBy         = $modified->getUser()->getUsername();
                $return['modified'] = array(
                    'date' => $modifiedDate,
                    'by'   => $modifiedBy,
                );
            }

            $created     = $entry->getCreationAudit();
            $createdDate = gmdate($this->dateFormat, $created->getTimestamp());
            $createdBy   = $created->getUser()->getUsername();

            $return['created'] = array(
                'date' => $createdDate,
                'by'   => $createdBy,
            );
        }

        return $return;
    }
}