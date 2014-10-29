<?php


namespace Elektra\SiteBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Registry;

interface EntityInterface
{

    /**
     * Return the id of the entity
     *
     * @return int
     */
    public function getId();

    /**
     * Return the representative title of the entity
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * create a slug from the display name
     *
     * @return string
     */
    public function slugifyDisplayName();
    /**
     * public function canBeDeleted(Registry $doctrine);
     * -> this functionality has to be covered by the specific controller
     */
}