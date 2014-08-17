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
use Elektra\SeedBundle\Entity\Trainings\Attendance;
use Elektra\SeedBundle\Entity\Trainings\Registration;
use Elektra\SeedBundle\Entity\Trainings\Training;
use Elektra\SeedBundle\Table\CRUDTable;
use Elektra\ThemeBundle\Table\Row;

/**
 * Class AttendanceTable
 *
 * @package Elektra\SeedBundle\Table\Trainings
 *
 * @version 0.1-dev
 */
class AttendanceTable extends CRUDTable
{

    /**
     * {@inheritdoc}
     */
    protected function setupHeader(Row $header)
    {

        // TODO add translations
        $idCell = $header->addCell();
        $idCell->setWidth(40);
        $idCell->addHtmlContent('ID');

        $trainingCell = $header->addCell();
        $trainingCell->addHtmlContent('Training');

        $registrantCell = $header->addCell();
        $registrantCell->addHtmlContent('Attendant');

        $partnerCell = $header->addCell();
        $partnerCell->addHtmlContent('Partner');
        $partnerCell->setColumnSpan(3);

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

        if (!$entry instanceof Attendance) {
            throw new \InvalidArgumentException('Can only display entries of type "Attendance"');
        }

        // ID
        $this->generateIdCell($content, $entry);

        $training = $content->addCell();
        $training->addHtmlContent(
            $entry->getTraining()->getName() . "(" . date('D, m/j/Y,g:i A', $entry->getTraining()->getStartedAt()) . " - " . date('D, m/j/Y,g:i A', $entry->getTraining()->getEndedAt()) . ")"
        );

        $registrant = $content->addCell();
        $registrant->addHtmlContent($entry->getPerson()->getTitle());

        $partner = $content->addCell();
        $partner->addHtmlContent($entry->getPerson()->getLocation()->getCompany()->getTitle());

        // Audits
        $this->generateAuditCell($content, $entry);

        // Actions
        $this->generateActionsCell($content, $entry);
    }
}