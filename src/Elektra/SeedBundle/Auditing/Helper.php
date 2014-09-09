<?php

namespace Elektra\SeedBundle\Auditing;

use Doctrine\Common\Collections\Collection;
use Elektra\SeedBundle\Entity\Auditing\Audit;

/**
 * Class Helper
 *
 * @package Elektra\SeedBundle\Auditing
 *
 * @version 0.1-dev
 */
abstract class Helper
{

    /**
     * @param Collection $audits
     *
     * @return Audit|null
     */
    public static function getFirstAudit(Collection $audits)
    {

        $firstAudit = null;

        foreach ($audits as $audit) {
            if ($audit instanceof Audit) {
                if ($firstAudit === null) {
                    $firstAudit = $audit;
                } else {
                    if ($firstAudit->getTimestamp() > $audit->getTimestamp()) {
                        $firstAudit = $audit;
                    }
                }
            }
        }

        return $firstAudit;
    }

    /**
     * @param Collection $audits
     *
     * @return Audit|null
     */
    public static function getLastAudit(Collection $audits)
    {

        $lastAudit = null;

        if (count($audits) < 2) {
            return $lastAudit;
        }

        foreach ($audits as $audit) {
            if ($audit instanceof Audit) {
                if ($lastAudit === null) {
                    $lastAudit = $audit;
                } else {
                    if ($lastAudit->getTimestamp() < $audit->getTimestamp()) {
                        $lastAudit = $audit;
                    }
                }
            }
        }

        return $lastAudit;
    }
}