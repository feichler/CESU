<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 15.09.14
 * Time: 16:32
 */

namespace Elektra\SeedBundle\Form;


use Doctrine\Common\Persistence\ObjectManager;
use Elektra\SeedBundle\Entity\SeedUnits\SalesStatus;
use Elektra\SeedBundle\Entity\SeedUnits\SeedUnit;
use Elektra\SeedBundle\Entity\SeedUnits\ShippingStatus;
use Elektra\SeedBundle\Entity\SeedUnits\UsageStatus;
use Elektra\SeedBundle\Form\Events\Types\ChangeSalesStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeShippingStatusType;
use Elektra\SeedBundle\Form\Events\Types\ChangeUsageStatusType;

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
            /** @var $seedUnit SeedUnit */
            if ($seedUnit->getRequest() != null)
            {
                $allowed = ShippingStatus::$ALLOWED_FROM[$seedUnit->getShippingStatus()->getInternalName()];
                $statuses = array_merge($statuses, $allowed);
            }
        }

        $statuses = array_unique($statuses);

        return $statuses;
    }

    public static function getAllowedShippingStatuses(ObjectManager $mgr, $seedUnits)
    {

        $names = FormsHelper::getAllowedStatuses($seedUnits);

        $statuses = array();
        if (count($names) > 0) {
            $repo = $mgr->getRepository('ElektraSeedBundle:SeedUnits\ShippingStatus');
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
            /** @var $status ShippingStatus */
            $shippingButtons[ChangeShippingStatusType::getModalId($status)] = $status->getTitle();
        }

        $usageButtons = array();
        foreach ($allowedUsages as $usage)
        {
            /** @var $usage UsageStatus */
            $usageButtons[ChangeUsageStatusType::getModalId($usage)] = $usage->getTitle();
        }

        $salesButtons = array();
        foreach ($allowedSalesStatuses as $status)
        {
            /** @var $status SalesStatus */
            $salesButtons[ChangeSalesStatusType::getModalId($status)] = $status->getTitle();
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