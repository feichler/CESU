<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Requests;

use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\WarehouseLocation;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\Requests\RequestCompletion;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\SeedBundle\Table\TableHelper;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class RequestCompletionTable
 *
 * @package Elektra\SeedBundle\Table\Requests
 *
 * @version 0.1-dev
 */
class RequestCompletionTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_Requests_RequestCompletion');
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

        $header->addCell()->addHtmlContent("Request #");
        $header->addCell()->addHtmlContent("Requesting Company");

        $lastCell = $header->addCell();
        $lastCell->addHtmlContent("Units Requested");
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

        if (!$entry instanceof RequestCompletion) {
            throw new \InvalidArgumentException('Can only display entries of type "RequestCompletion"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $viewLink   = $this->generateLink('view', $entry->getId());
        $identifier = $content->addCell();
        $identifier->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $content->addCell()->addHtmlContent($entry->getCompany()->getTitle());
        $content->addCell()->addHtmlContent($entry->getRequest()->getNumberOfUnitsRequested());


        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}