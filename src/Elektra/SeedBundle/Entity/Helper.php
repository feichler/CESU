<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 11.08.14
 * Time: 10:27
 */

namespace Elektra\SeedBundle\Entity;

use Elektra\SeedBundle\Entity\Auditing\Audit;
use Elektra\UserBundle\Entity\User;

class Helper
{
    /**
     * @return Audit
     */
    public static function createAudit()
    {
        $audit = new Audit();
        $audit->setCreatedAt(Helper::getCurrentTimestamp());
        $audit->setCreatedBy(Helper::getCurrentUser());
        return $audit;
    }

    /**
     * @param Audit $audit
     */
    public static function updateAudit($audit)
    {
        $audit->setModifiedBy(Helper::getCurrentUser());
        $audit->setModifiedAt(Helper::getCurrentTimestamp());
    }

    /**
     * @return User
     */
    private static function getCurrentUser()
    {
        return null;
    }

    /**
     * @return int
     */
    private static function getCurrentTimestamp()
    {
        return time();
    }
} 