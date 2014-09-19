<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 15.09.14
 * Time: 16:32
 */

namespace Elektra\SeedBundle\Form;


use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Entity\Events\UnitStatus;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUnitUsageType;

class FormsHelper {

    /**
     * @param array $seedUnits
     * @return array List containing the internal names of the allowed statuses
     */
    public static function getAllowedStatuses(array $seedUnits)
    {

        $statuses = array();
        foreach($seedUnits as $seedUnit)
        {
            if ($seedUnit->getRequest() != null)
            {
                $allowed = UnitStatus::$ALLOWED_FROM[$seedUnit->getShippingStatus()->getInternalName()];
                $statuses = array_merge($statuses, $allowed);
            }
        }

        $statuses = array_unique($statuses);

        return $statuses;
    }

    public static function getAllowedUnitStatuses(ObjectManager $mgr, $seedUnits)
    {

        $names = FormsHelper::getAllowedStatuses($seedUnits);

        $statuses = array();
        if (count($names) > 0) {
            $repo = $mgr->getRepository('ElektraSeedBundle:Events\UnitStatus');
            $qb   = $repo->createQueryBuilder('us');
            $qb->where($qb->expr()->in('us.internalName', $names));
            $statuses = $qb->getQuery()->getResult();
        }

        return $statuses;
    }

    public static function createModalButtonsOptions(array $allowedStatuses, array $allowedUsages, array $allowedSalesStatuses)
    {
        $shippingButtons = array();
        foreach ($allowedStatuses as $status)
        {
            $shippingButtons[ChangeUnitStatusType::getModalId($status)] = $status->getTitle();
        }

        $usageButtons = array();
        foreach ($allowedUsages as $usage)
        {
            $usageButtons[ChangeUnitUsageType::getModalId($usage)] = $usage->getTitle();
        }

        $salesButtons = array();
        foreach ($allowedSalesStatuses as $status)
        {
            $salesButtons[ChangeUnitSalesStatusType::getModalId($status)] = $status->getTitle();
        }

        $buttons = array(
            'Shipping' => $shippingButtons,
            'Usage' => $usageButtons,
            'Sales' => $salesButtons
        );

        foreach ($buttons as $key => $value)
        {
            if (count($value) == 0)
            {
                unset($buttons[$key]);
            }
        }

        return $buttons;
    }
}