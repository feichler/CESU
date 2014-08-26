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

        if(!$this->isEmbedded()) {
            $region = $this->getColumns()->add('table.companies.country.region');
            $region->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region'));
            $region->setFieldData('region.name');
            $region->setFilterable()->setFieldFilter('name');
            $region->setSortable();
        } else {
            if($this->getEmbedded()->getName() == 'Region') {
                // ??
            }
        }

//        var_dump($this->isEmbedded());
//        echo '<br />';
//        var_dump($this->embedded);
//        echo '<br />';
//
//        if (!$this->isEmbedded() && $this->embedded->getName() != 'Region') {
//            $region = $this->getColumns()->add('table.companies.country.region');
//            $region->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region'));
//            $region->setFieldData('region.name');
//            $region->setFilterable()->setFieldFilter('name');
//            $region->setSortable();
//        }
    }
}