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
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SeedBundle\Table\TableHelper;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class WarehouseLocationTable
 *
 * @package Elektra\SeedBundle\Table\Companies
 *
 * @version 0.1-dev
 */
class WarehouseLocationTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_Companies_WarehouseLocation');
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

        $header->addCell()->addHtmlContent("Identifier");
        $header->addCell()->addHtmlContent("Short Name");
        $header->addCell()->addHtmlContent("Name");

        $addressCell = $header->addCell();
        $addressCell->addHtmlContent("Address");
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

        if (!$entry instanceof WarehouseLocation) {
            throw new \InvalidArgumentException('Can only display entries of type "WarehouseLocation"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $viewLink  = $this->generateLink('view', $entry->getId());
        $identifier = $content->addCell();
        $identifier->addActionContent('view', $viewLink, array('text' => $entry->getLocationIdentifier(), 'render' => 'link'));

        $content->addCell()->addHtmlContent($entry->getShortName());
        $content->addCell()->addHtmlContent($entry->getName());

        $addressCell = $content->addCell();
/*        $address = $entry->getAddresses()->first();
        if ($address != null)
        {
            $addressCell->addHtmlContent(TableHelper::renderAddress($address));
        }*/

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}