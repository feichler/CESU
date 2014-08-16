<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\SeedUnits;

use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitPowerCordType;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class SeedUnitPowerCordTypeTable
 *
 * @package Elektra\SeedBundle\Table\SeedUnits
 *
 *          @version 0.1-dev
 */
class SeedUnitPowerCordTypeTable extends CRUDTable
{
    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_SeedUnits_PowerCordType');
    }
    /**
     * {@inheritdoc}
     */
    protected function setupHeader(Row $header)
    {

        // TODO add translations
        $idCell = $header->addCell();
        $idCell->setWidth(40);
        $idCell->addHtmlContent('ID');

        $titleCell = $header->addCell();
        $titleCell->addHtmlContent('Power Cord Type');
        $titleCell->setColumnSpan(3);

        // TODO src should audits and actions have an own header cell?
        //        $auditCell = $header->addCell();
        //        $auditCell->setWidth(100);
        //
        //        $actionsCell = $header->addCell();
        //        $actionsCell->setWidth(150);
    }
    /**
     * {@inheritdoc}
     */
    protected function setupContentRow(Row $content,CRUDEntityInterface $entry)
    {

        if (!$entry instanceof SeedUnitPowerCordType) {
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