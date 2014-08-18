<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\SeedBundle\Entity\Companies\ContactInfo;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class ContactInfoTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class ContactInfoTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_Companies_ContactInfo');
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

        $header->addCell()->addHtmlContent("Person");
        $header->addCell()->addHtmlContent("Name");
        $header->addCell()->addHtmlContent("Type");
        $lastCell = $header->addCell();
        $lastCell->addHtmlContent("Text");
        $lastCell->setColumnSpan(3);

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

        if (!$entry instanceof ContactInfo) {
            throw new \InvalidArgumentException('Can only display entries of type "ContactInfo"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $content->addCell()->addHtmlContent($entry->getPerson()->getTitle());

        $viewLink = $this->generateLink('view', $entry->getId());
        $nameCell = $content->addCell();
        $nameCell->addActionContent('view', $viewLink, array('text' => $entry->getName(), 'render' => 'link'));
        $content->addCell()->addHtmlContent($entry->getText());

        $content->addCell()->addHtmlContent($entry->getContactInfoType()->getName());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}