<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table\Trainings;

use Elektra\SeedBundle\Entity\CRUDEntityInterface;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnitModel;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class TrainingTable
 *
 * @package Elektra\SeedBundle\Table\Trainings
 *
 * @version 0.1-dev
 */
class TrainingTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupType()
    {

        $this->setParam('routePrefix', 'ElektraSeedBundle_MasterData_Trainings_Training');
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
        $titleCell->addHtmlContent('Model');
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
    protected function setupContentRow(Row $content, CRUDEntityInterface $entry)
    {

        if (!$entry instanceof SeedUnitModel) {
            throw new \InvalidArgumentException('Can only display entries of type "SeedUnitModel"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        // Name
        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $titleCell = $content->addCell();
        $titleCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $startetAt = $content->addCell();
        $startetAt->addHtmlContent($entry->getStartedAt());

        $endedAt = $content->addCell();
        $endedAt->addHtmlContent($entry->getEndedAt());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}