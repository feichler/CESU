<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SeedBundle\Table\TableHelper;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class CompanyPersonTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class CompanyPersonTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_Companies_CompanyPerson');
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

        $header->addCell()->addHtmlContent("Company");
        $header->addCell()->addHtmlContent("Location");
        $header->addCell()->addHtmlContent("IsPrimary");

        $header->addCell()->addHtmlContent("First Name");
        $header->addCell()->addHtmlContent("Last Name");
        $header->addCell()->addHtmlContent("Salutation");


        $addressCell = $header->addCell();
        $addressCell->addHtmlContent("Job Title");
        $addressCell->setColumnSpan(3);

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

        if (!$entry instanceof CompanyPerson) {
            throw new \InvalidArgumentException('Can only display entries of type "CompanyPerson"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $content->addCell()->addHtmlContent($entry->getLocation()->getCompany()->getTitle());
        $content->addCell()->addHtmlContent($entry->getLocation()->getTitle());
        $content->addCell()->addHtmlContent($entry->getIsPrimary());

        $viewLink  = $this->generateLink('view', $entry->getId());
        $firstNameCell = $content->addCell();
        $firstNameCell->addActionContent('view', $viewLink, array('text' => $entry->getFirstName(), 'render' => 'link'));
        $lastNameCell = $content->addCell();
        $lastNameCell->addActionContent('view', $viewLink, array('text' => $entry->getLastName(), 'render' => 'link'));

        $content->addCell()->addHtmlContent($entry->getSalutation());
        $content->addCell()->addHtmlContent($entry->getJobTitle());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}