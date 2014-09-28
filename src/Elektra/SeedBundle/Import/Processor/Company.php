<?php

namespace Elektra\SeedBundle\Import\Processor;

use Elektra\SeedBundle\Import\Processor;

class Company extends Processor
{

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function checkAndPrepareRowData(array &$data, $row)
    {

        return $this->helper->checkCreateCompany($data, $row, true);
    }

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function storeRowData(array $data, $row)
    {

        return $this->helper->createCompany($data, $row);
    }
}
