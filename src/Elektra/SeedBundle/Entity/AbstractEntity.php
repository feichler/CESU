<?php

namespace Elektra\SeedBundle\Entity;

use Elektra\SiteBundle\Entity\AbstractEntity as BaseEntity;

abstract class AbstractEntity extends BaseEntity
{

    /**
     * Constructor
     *
     * @inheritdoc
     */
    public function __construct()
    {

        parent::__construct();
    }
}