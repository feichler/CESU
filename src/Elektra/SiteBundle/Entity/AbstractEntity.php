<?php

namespace Elektra\SiteBundle\Entity;

use Elektra\SiteBundle\Utility\StringUtility;

/**
 *
 */
abstract class AbstractEntity implements EntityInterface
{

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public function slugifyDisplayName()
    {

        $slugFrom = $this->getDisplayName();
        $slug     = StringUtility::slugify($slugFrom);

        return $slug;
    }
}