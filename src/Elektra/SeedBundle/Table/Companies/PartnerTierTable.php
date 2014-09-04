<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class PartnerTierTable extends Table
{

    protected function initialiseColumns()
    {

        $name = $this->getColumns()->addTitleColumn('tables.companies.partner_tier.name');
        $name->setFieldData('name');
        $name->setSearchable();
        $name->setSortable();

        $unitLimit = $this->getColumns()->add('tables.companies.partner_tier.units_limit');
        $unitLimit->setFieldData('unitsLimit');
    }
}