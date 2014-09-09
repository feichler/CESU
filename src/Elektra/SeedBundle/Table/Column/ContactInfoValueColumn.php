<?php

namespace Elektra\SeedBundle\Table\Column;

use Elektra\CrudBundle\Table\Column\TitleColumn;
use Elektra\CrudBundle\Table\Columns;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;

class ContactInfoValueColumn extends TitleColumn

{

    public function __construct(Columns $columns, $title = '')
    {

        parent::__construct($columns, $title);
    }

    public function getDisplayData($entry, $rowNumber)
    {

        if (!($entry instanceof ContactInfo)) {
            throw new \InvalidArgumentException('Can only use entries of type "ContactInfo"');
        }

        $return = parent::getDisplayData($entry, $rowNumber);

        $return['class'] = $entry->getContactInfoType()->getInternalName();

        return $return;
    }
}