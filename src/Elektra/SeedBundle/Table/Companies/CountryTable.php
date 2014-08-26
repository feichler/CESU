<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Table\Table;

class CountryTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $country = $this->getColumns()->addTitleColumn('table.companies.country.country');
        $country->setFieldData('name');
        $country->setSearchable();
        $country->setSortable();

        $region = $this->getColumns()->add('table.companies.country.region');
        $region->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region'));
        $region->setFieldData('region.name');
        $region->setFilterable()->setFieldFilter('name');
        $region->setSortable();

        if ($this->getCrud()->isEmbedded() && $this->getCrud()->getEmbeddedRelationName() == 'region') {
            $region->setHidden();
        }
    }
}