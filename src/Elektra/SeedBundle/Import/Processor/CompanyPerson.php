<?php

namespace Elektra\SeedBundle\Import\Processor;

use Elektra\SeedBundle\Import\Processor;

class CompanyPerson extends Processor
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

        /**
         * Check the company location data
         */
        $location = $this->helper->loadCompanyLocation($data, $row);
        if ($location === false) {
            // company location data is invalid (invalid data or found in database but not matching)
            $valid = false;
        } else if ($location === null) {
            // company location not found in database
            // try to create the company location
            $check = $this->helper->checkCreateCompanyLocation($data, $row, false);
            if (!$check) {
                // company location data is invalid for creation
                $valid = false;
            } else {
                // create the company location now
                $this->helper->createCompanyLocation($data, $row);
            }
        }

        if (!$valid) {
            // cannot check further if not valid till now
            return false;
        }

        $personCheck = $this->helper->checkCreateCompanyPerson($data, $row);
        if (!$personCheck) {
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
        return $this->helper->createCompanyPerson($data,$row);
    }
}
