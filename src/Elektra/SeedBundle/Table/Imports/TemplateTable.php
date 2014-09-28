<?php

namespace Elektra\SeedBundle\Table\Imports;

use Elektra\CrudBundle\Table\Table;
use Elektra\SeedBundle\Table\Column\TemplateColumn;

class TemplateTable extends Table
{

    protected function initialiseActions()
    {

        $this->disallowAction('view');
        $this->disallowAction('edit');
        $this->disallowAction('delete');
        $this->disallowAction('add');
    }

    /**
     * {@inheritdoc}
     */
    protected function initialiseColumns()
    {

        $template = $this->getColumns()->addTitleColumn('name');
        $template->setFieldData('name');
        $template->setSortable()->setFieldSort('name');

        $download = new TemplateColumn($this->getColumns());
        $this->getColumns()->addColumn($download, null);



        /*        $tier = $this->getColumns()->add('template_tier');
                $tier->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Imports', 'TemplateTier'));
                $tier->setFieldData('templateTier.name');
                $tier->setFilterable()->setFieldFilter('name');
                $tier->setSortable();*/

        //        $tier = $this->getColumns()->add('template_type');
        //        $tier->setDefinition($this->getCrud()->getDefinition('Elektra', 'Seed', 'Imports', 'TemplateType'));
        //        $tier->setFieldData('templateType.name');
        //        $tier->setFilterable()->setFieldFilter('name');
        //        $tier->setSortable();
        /*        $limit = $this->getColumns()->add('units_limit');
                $limit->setFieldData('unitsLimit');
                $limit->setSortable();*/
    }
}