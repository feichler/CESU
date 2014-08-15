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

/**
 * Class Customer
 *
 * @package Elektra\SeedBundle\Entity\Companies
 *
 *          @version 0.1-dev
 *
 * @ORM\Entity
 * @ORM\Table(name="customerCompanies")
 */
class Customer extends Company
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }
}