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
use Elektra\SeedBundle\Entity\Trainings\Training;
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
        $titleCell->addHtmlContent('Name');

        $startedAtCell = $header->addCell();
        $startedAtCell->addHtmlContent('Start');

        $endedAtCell = $header->addCell();
        $endedAtCell->addHtmlContent('End');

        $attendancesCell = $header->addCell();
        $attendancesCell->addHtmlContent('# Att');

        $registrationsCell = $header->addCell();
        $registrationsCell->addHtmlContent('# Reg');
        $registrationsCell->setColumnSpan(3);

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

        if (!$entry instanceof Training) {
            throw new \InvalidArgumentException('Can only display entries of type "Training"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        // Name
//        $viewLink  = $this->generateLink($this->getRoute('view'), $entry->getId());
        $viewLink  = $this->generateLink('view', $entry->getId());
        $titleCell = $content->addCell();
        $titleCell->addActionContent('view', $viewLink, array('text' => $entry->getTitle(), 'render' => 'link'));

        $startedAt = $content->addCell();
        $startedAt->addHtmlContent(date('D, m/j/Y,g:i A', $entry->getStartedAt()));

        $endedAt = $content->addCell();
        $endedAt->addHtmlContent(date('D, m/j/Y,g:i A', $entry->getEndedAt()));

        $attendances = $content->addCell();
        $attendances->addHtmlContent(count($entry->getAttendances()));

        $registrations = $content->addCell();
        $registrations->addHtmlContent(count($entry->getRegistrations()));

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}