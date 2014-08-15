<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SeedBundle\Table;

use Elektra\ThemeBundle\Table\Table as BaseTable;

/**
 * Class Table
 *
 * @package Elektra\SeedBundle\Table
 *
 * @version 0.1-dev
 */
abstract class Table extends BaseTable
{

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
    }
}