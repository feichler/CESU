<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class PartnerTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $partner = $this->getColumns()->addTitleColumn('table.companies.partner.name');
        $partner->setFieldData(array('shortName', 'name'));
        $partner->setSearchable();
        $partner->setSortable()->setFieldSort('shortName');

        $tier = $this->getColumns()->add('table.companies.partner.tier');
        $tier->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier'));
        $tier->setFieldData('partnerTier.name');
        $tier->setFilterable()->setFieldFilter('name');
        $tier->setSortable();

        $limit = $this->getColumns()->add('table.companies.partner.units_limit');
        $limit->setFieldData('unitsLimit');
        $limit->setSortable();
    }
}