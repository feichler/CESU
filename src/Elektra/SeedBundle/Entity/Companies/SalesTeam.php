<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class SalesTeam
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 * @version 0.1-dev
 *
 * @ORM\Entity(repositoryClass="Elektra\SeedBundle\Repository\Companies\SalesTeamRepository")
 * @ORM\Table(name="salesTeamCompanies")
 * @UniqueEntity(fields={ "shortName" }, message="")
 */
class SalesTeam extends RequestingCompany
{

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Used for grouping (translation key)
     *
     * @return string
     */
    public function getCompanyType()
    {

        return 'forms.requests.request.companies.sales_team';
    }
}