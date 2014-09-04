<?php

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\CrudBundle\Crud\Definition;
use Elektra\CrudBundle\Table\Table;

class CountryTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $country = $this->getColumns()->addTitleColumn('tables.companies.country.country');
        $country->setFieldData('name');
        $country->setSearchable();
        $country->setSortable();

        $region = $this->getColumns()->add('tables.companies.country.region');
        $region->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Region'));
        $region->setFieldData('region.name');
        $region->setFilterable()->setFieldFilter('name');
        $region->setSortable();

        if ($this->getCrud()->isEmbedded()) {
//            echo 'Route: ' . $this->getCrud()->getLinker()->getActiveRoute().'<br />';
//            $embeddingDefinition = $this->getCrud()->getEmbeddingDefinition();
//            echo $embeddingDefinition->getName().'<br />';
//            echo $this->getCrud()->getEmbeddedType().'<br />';
            if ($this->getCrud()->getParentDefinition()->getName() == 'Region') {
                $region->setHidden();
            }
        }
    }

    protected function getRelationFilterName(Definition $parentDefinition)
    {

        if ($parentDefinition->getName() == 'Region') {
            return 'region';
        }
    }
}