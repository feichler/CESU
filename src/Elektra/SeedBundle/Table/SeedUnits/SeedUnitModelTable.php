<?php

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

class SeedUnitModelTable extends CRUDTable
{

    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_SeedUnits_Model');
    }

    protected function setupHeader(Row $header)
    {

        // TODO add translations
        $idCell = $header->addCell();
        $idCell->setWidth(40);
        $idCell->addHtmlContent('ID');

        $titleCell = $header->addCell();
        $titleCell->addHtmlContent('Model');
        $titleCell->setColumnSpan(3);

        // TODO src should audits and actions have an own header cell?
        //        $auditCell = $header->addCell();
        //        $auditCell->setWidth(100);
        //
        //        $actionsCell = $header->addCell();
        //        $actionsCell->setWidth(150);
    }

    protected function setupContentRow(Row $content, $entry)
    {

        if (!$entry instanceof SeedUnitModel) {
            throw new \InvalidArgumentException('Can only display entries of type "SeedUnitModel"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        // Name & Description
        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $modelCell = $content->addCell();
        $modelCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));
        $description = $modelCell->addHtmlContent($entry->getDescription());
        $description->setContainer('div');

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}