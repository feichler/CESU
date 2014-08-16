<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\SeedUnits;

use Doctrine\Common\Collections\Criteria;
use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\Events\StatusEvent;
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

        $this->setParam('routePrefix', 'ElektraSeedBundle_SeedUnit');
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

        $serialNumberCell = $header->addCell();
        $serialNumberCell->addHtmlContent('Serial Number');
        $serialNumberCell->setColumnSpan(3);

        $modelCell = $header->addCell();
        $modelCell->addHtmlContent('Model');

        $powerCordTypeCell = $header->addCell();
        $powerCordTypeCell->addHtmlContent('Power Cord Type');

        $statusCell = $header->addCell();
        $statusCell->addHtmlContent('Status');

        $requestCell = $header->addCell();
        $requestCell->addHtmlContent('Request');

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
    protected function setupContentRow(Row $content,CRUDEntityInterface $entry)
    {

        if (!$entry instanceof SeedUnit) {
            throw new \InvalidArgumentException('Can only display entries of type "SeedUnit"');
        }

        // ID
        $this->generateIdCell($content, $entry);

//        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $viewLink  = $this->generateLink('view', $entry->getId());
        $seedUnitCell = $content->addCell();
        $seedUnitCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $modelCell = $content->addCell();
        $modelCell->addHtmlContent($entry->getModel()->getTitle());

        $powerCordTypeCell = $content->addCell();
        $powerCordTypeCell->addHtmlContent($entry->getPowerCordType()->getTitle());

        //$statusCriteria = Criteria::create();
        //$statusCriteria->where(Criteria::expr()->eq('type', 'StatusEvent'));
        //$status = $entry->getEvents()->matching($statusCriteria);

        //HACK: using filter() instead of matching() (see above) because being unable to match by subtype/discriminator
        //--> forces EAGER LOADING!!
        $status = $entry->getEvents()->filter(function($event) {
            return $event instanceof StatusEvent;
        })->first();

        $statusCell = $content->addCell();
        if ($status != null)
        {
            $statusCell->addHtmlContent($status->getUnitStatus()->getName());
            $statusCell->addHtmlContent(" (since " . date("D, m/j/Y", $status->getTimestamp()) . ")");
        }

        $requestCell = $content->addCell();
        if ($entry->getRequest() != null)
        {
            $requestCell->addHtmlContent($entry->getRequest()->getId());
        }

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}