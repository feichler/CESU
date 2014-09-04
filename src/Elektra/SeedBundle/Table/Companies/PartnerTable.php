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

        $partner = $this->getColumns()->addTitleColumn('tables.companies.company.name');
        $partner->setFieldData(array('shortName', 'name'));
        $partner->setSearchable();
        $partner->setSortable()->setFieldSort('shortName');

        $tier = $this->getColumns()->add('tables.companies.partner.partner_tier');
        $tier->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerTier'));
        $tier->setFieldData('partnerTier.name');
        $tier->setFilterable()->setFieldFilter('name');
        $tier->setSortable();

        $limit = $this->getColumns()->add('tables.companies.partner.units_limit');
        $limit->setFieldData('unitsLimit');
        $limit->setSortable();
    }
}