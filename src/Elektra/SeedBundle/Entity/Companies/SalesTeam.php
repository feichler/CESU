<?php

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SalesTeam
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @ORM\Entity
 * @ORM\Table(name="salesTeamCompanies")
 */
class SalesTeam extends Company
{
    public function __construct()
    {
        parent::__construct();
    }
}