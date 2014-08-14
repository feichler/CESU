<?php

namespace Elektra\SeedBundle\Entity;

interface CRUDEntityInterface
{

    /**
     * Return the id of the entry
     *
     * @return int
     */
    public function getId();

    /**
     * Return the representative title of the entity
     *
     * @return string
     */
    public function getTitle();
}