<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Companies;

use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\TableHelper;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class PartnerTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class PartnerTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupHeader(Row $header)
    {

        // TRANSLATE add translations for the table headers
        $idCell = $header->addCell();
        $idCell->setWidth(40);
        $idCell->addHtmlContent('ID');

        $shortNameCell = $header->addCell();
        $shortNameCell->addHtmlContent('Short Name');

        $nameCell = $header->addCell();
        $nameCell->addHtmlContent('Name');

        $tierCell = $header->addCell();
        $tierCell->addHtmlContent('Tier');

        $unitsLimitCell = $header->addCell();
        $unitsLimitCell->addHtmlContent('Units Limit');
        $unitsLimitCell->setColumnSpan(3);

        $primaryLocationCell = $header->addCell();
        $primaryLocationCell->addHtmlContent('Prim. Location');

        $countCustomersCell = $header->addCell();
        $countCustomersCell->addHtmlContent('# Customers');

        $countUnitsCell = $header->addCell();
        $countUnitsCell->addHtmlContent('# Seed Units');

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

        if (!$entry instanceof Partner) {
            throw new \InvalidArgumentException('Can only display entries of type "Partner"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        // Name
        $viewLink      = $this->generateLink('view', $entry->getId());
        $shortNameCell = $content->addCell();
        $shortNameCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $name = $content->addCell();
        $name->addHtmlContent($entry->getName());

        //TODO: realize as link to partner tier
        $partnerTier = $content->addCell();
        $partnerTier->addHtmlContent($entry->getPartnerTier()->getTitle());

        $unitsLimit = $content->addCell();
        $unitsLimit->addHtmlContent($entry->getUnitsLimit());

        $primaryLocationCell = $content->addCell();
        $location            = $entry->getPrimaryLocation();
        $address             = $location != null ? $location->getAddresses()->first() : null;
        if ($address != null) {
            $primaryLocationCell->addHtmlContent(TableHelper::renderAddress($address));
        }

        $countCustomers = $content->addCell();
        //TODO: realize
        //$countCustomers->addHtmlContent(count($entry->getCustomers()));

        $countUnits = $content->addCell();
        //TODO: realize
        //$countUnits->addHtmlContent(count($entry->()));

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}