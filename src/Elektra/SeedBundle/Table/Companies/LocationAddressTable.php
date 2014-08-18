<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\LocationAddress;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SeedBundle\Table\TableHelper;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class LocationAddressTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class LocationAddressTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_Companies_LocationAddress');
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

        $header->addCell()->addHtmlContent("Name");
        $header->addCell()->addHtmlContent("Street 1");
        $header->addCell()->addHtmlContent("Postal Code");
        $header->addCell()->addHtmlContent("City");
        $header->addCell()->addHtmlContent("State");

        $addressCell = $header->addCell();
        $addressCell->addHtmlContent("Country");
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

        if (!$entry instanceof LocationAddress) {
            throw new \InvalidArgumentException('Can only display entries of type "LocationAddress"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $companyCell = $content->addCell();
        if ($entry->getLocation() instanceof CompanyLocation) {
            $companyCell->addHtmlContent($entry->getLocation()->getCompany()->getTitle());
        }

        $content->addCell()->addHtmlContent($entry->getLocation()->getTitle());

        $viewLink = $this->generateLink('view', $entry->getId());
        $nameCell = $content->addCell();
        $nameCell->addActionContent('view', $viewLink, array('text' => $entry->getName(), 'render' => 'link'));

        $content->addCell()->addHtmlContent($entry->getStreet1());
        $content->addCell()->addHtmlContent($entry->getPostalCode());
        $content->addCell()->addHtmlContent($entry->getCity());
        $content->addCell()->addHtmlContent($entry->getState());
        $content->addCell()->addHtmlContent($entry->getCountry()->getName());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}