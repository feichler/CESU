<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\SeedBundle\Entity\Companies\PartnerTier;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class PartnerTierTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class PartnerTierTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_Companies_PartnerTier');
    }

    /**
     * {@inheritdoc}
     */
    protected function setupHeader(Row $header)
    {

        // TRANSLATE add translations for the table headers
        $idCell = $header->addCell();
        $idCell->setWidth(40);
        $idCell->addHtmlContent('ID');

        $titleCell = $header->addCell();
        $titleCell->addHtmlContent('Name');

        $unitsLimitCell = $header->addCell();
        $unitsLimitCell->addHtmlContent('Units Limit');
        $unitsLimitCell->setColumnSpan(3);

        // CHECK should audits and actions have an own header cell?
        //        $auditCell = $header->addCell();
        //        $auditCell->setWidth(100);
        //
        //        $actionsCell = $header->addCell();
        //        $actionsCell->setWidth(150);
    }

    /**
     * {@inheritdoc}
     */
    protected function setupContentRow(Row $content, CRUDEntityInterface $entry)
    {

        if (!$entry instanceof PartnerTier) {
            throw new \InvalidArgumentException('Can only display entries of type "PartnerTier"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        // Name & Description
//        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $viewLink  = $this->generateLink('view', $entry->getId());
        $modelCell = $content->addCell();
        $modelCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $unitsLimitCell = $content->addCell();
        $unitsLimitCell->addHtmlContent($entry->getUnitsLimit());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}