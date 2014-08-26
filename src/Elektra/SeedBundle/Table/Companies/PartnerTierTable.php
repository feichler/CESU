<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class PartnerTierTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('table.companies.partnerTier.name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();

        $unitLimit = $this->getColumns()->add('table.companies.partnerTier.unitsLimit');
        $unitLimit->setFieldData('unitsLimit');
    }
}