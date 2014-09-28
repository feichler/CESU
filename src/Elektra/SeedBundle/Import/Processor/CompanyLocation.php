<?php

namespace Elektra\SeedBundle\Import\Processor;

use Elektra\SeedBundle\Import\Processor;

class CompanyLocation extends Processor
{

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function checkAndPrepareRowData(array &$data, $row)
    {

        $valid = true;

        /**
         * Check the company data
         */
        $company = $this->helper->loadCompany($data, $row);
        if ($company === false) {
            // company data is invalid (invalid data or found in database but not matching)
            $valid = false;
        } else if ($company === null) {
            // company not found in database
            // try to create the company
            $check = $this->helper->checkCreateCompany($data, $row, false);
            if (!$check) {
                // company data is invalid for creation
                $valid = false;
            } else {
                // create the company now
                $this->helper->createCompany($data, $row);
            }
        }

        if (!$valid) {
            // cannot check further if not valid till now
            return false;
        }

        /*
         * Check the location data
         */
        $locationCheck = $this->helper->checkCreateCompanyLocation($data, $row);
        if (!$locationCheck) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * @param array $data
     * @param int   $row
     *
     * @return bool
     */
    protected function storeRowData(array $data, $row)
    {

        return $this->helper->createCompanyLocation($data, $row);
    }
}
