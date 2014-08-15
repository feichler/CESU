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
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class SeedUnitTable
 *
 * @package Elektra\SeedBundle\Table\SeedUnits
 *
 *          @version 0.1-dev
 */
class SeedUnitTable extends CRUDTable
{
    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_SeedUnits_SeedUnit');
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

        $serialNumberCell = $header->addCell();
        $serialNumberCell->addHtmlContent('Serial Number');
        $serialNumberCell->setColumnSpan(3);

        $modelCell = $header->addCell();
        $modelCell->addHtmlContent('Model');

        $powerCordTypeCell = $header->addCell();
        $powerCordTypeCell->addHtmlContent('Power Cord Type');

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

        if (!$entry instanceof SeedUnit) {
            throw new \InvalidArgumentException('Can only display entries of type "SeedUnit"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $seedUnitCell = $content->addCell();
        $seedUnitCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $viewModelLink  = $this->generateLink($this->getRoute('view'), $entry->getModel()->getId());
        $modelCell = $content->addCell();
        $modelCell->addActionContent('view', $viewModelLink, array('text' => $entry->getModel()->getTitle(), 'render' => 'link'));

        $viewPowerCordTypeLink = $this->generateLink($this->getRoute('view'), $entry->getPowerCordType()->getId());
        $modelCell = $content->addCell();
        $modelCell->addActionContent('view', $viewPowerCordTypeLink, array('text' => $entry->getPowerCordType()->getTitle(), 'render' => 'link'));

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}