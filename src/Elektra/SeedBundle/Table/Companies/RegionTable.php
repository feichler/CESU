<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class RegionTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $region = $this->getColumns()->addTitleColumn('table.companies.region.region');
        $region->setFieldData('name');
        $region->setSearchable();
        $region->setSortable();

        $countries = $this->getColumns()->addCountColumn('table.companies.region.countries');
        $countries->setFieldData('countries');
        $countries->setSortable();
    }
}