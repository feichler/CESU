<?php

namespace Elektra\SeedBundle\Definition;

use Elektra\CrudBundle\Definition\Definition;

class CompanyDefinitions
{
    private static $definitions;

    /**
     * @return Definition
     */
    public static function getAddress()
    {

        return CompanyDefinitions::$definitions['Address'];
    }

    /**
     * @return Definition
     */
    public static function getAddressType()
    {

        return CompanyDefinitions::$definitions['AddressType'];
    }

    /**
     * @return Definition
     */
    public static function getCompanyLocation()
    {

        return CompanyDefinitions::$definitions['CompanyLocation'];
    }

    /**
     * @return Definition
     */
    public static function getCompanyPerson()
    {

        return CompanyDefinitions::$definitions['CompanyPerson'];
    }

    /**
     * @return Definition
     */
    public static function getContactInfo()
    {

        return CompanyDefinitions::$definitions['ContactInfo'];
    }

    /**
     * @return Definition
     */
    public static function getContactInfoType()
    {

        return CompanyDefinitions::$definitions['ContactInfoType'];
    }

    /**
     * @return Definition
     */
    public static function getCountry()
    {

        return CompanyDefinitions::$definitions['Country'];
    }

    /**
     * @return Definition
     */
    public static function getCustomer()
    {

        return CompanyDefinitions::$definitions['Customer'];
    }

    /**
     * @return Definition
     */
    public static function getLocation()
    {

        return CompanyDefinitions::$definitions['Location'];
    }

    /**
     * @return Definition
     */
    public static function getPartner()
    {

        return CompanyDefinitions::$definitions['Partner'];
    }

    /**
     * @return Definition
     */
    public static function getPartnerTier()
    {

        return CompanyDefinitions::$definitions['PartnerTier'];
    }

    /**
     * @return Definition
     */
    public static function getPerson()
    {

        return CompanyDefinitions::$definitions['Person'];
    }

    /**
     * @return Definition
     */
    public static function getRegion()
    {

        return CompanyDefinitions::$definitions['Region'];
    }

    /**
     * @return Definition
     */
    public static function getRequestingCompany()
    {

        return CompanyDefinitions::$definitions['RequestingCompany'];
    }

    /**
     * @return Definition
     */
    public static function getSalesTeam()
    {

        return CompanyDefinitions::$definitions['SalesTeam'];
    }

    /**
     * @return Definition
     */
    public static function getWarehouseLocation()
    {

        return CompanyDefinitions::$definitions['WarehouseLocation'];
    }

    public static function getAll()
    {

        return array_values(CompanyDefinitions::$definitions);
    }

    public static function initialize()
    {
        CompanyDefinitions::$definitions = array(
            'Address'           => new Definition('Elektra', 'Seed', 'Companies', 'Address'),
            'AddressType'       => new Definition('Elektra', 'Seed', 'Companies', 'AddressType'),
            'CompanyLocation'   => new Definition('Elektra', 'Seed', 'Companies', 'CompanyLocation'),
            'CompanyPerson'     => new Definition('Elektra', 'Seed', 'Companies', 'CompanyPerson'),
            'ContactInfo'       => new Definition('Elektra', 'Seed', 'Companies', 'ContactInfo'),
            'ContactInfoType'   => new Definition('Elektra', 'Seed', 'Companies', 'ContactInfoType'),
            'Country'           => new Definition('Elektra', 'Seed', 'Companies', 'Country'),
            'Customer'          => new Definition('Elektra', 'Seed', 'Companies', 'Customer'),
            'Location'          => new Definition('Elektra', 'Seed', 'Companies', 'Location'),
            'Partner'           => new Definition('Elektra', 'Seed', 'Companies', 'Partner'),
            'PartnerTier'       => new Definition('Elektra', 'Seed', 'Companies', 'PartnerTier'),
            'Person'            => new Definition('Elektra', 'Seed', 'Companies', 'Person'),
            'Region'            => new Definition('Elektra', 'Seed', 'Companies', 'Region'),
            'RequestingCompany' => new Definition('Elektra', 'Seed', 'Companies', 'RequestingCompany'),
            'SalesTeam'         => new Definition('Elektra', 'Seed', 'Companies', 'SalesTeam'),
            'WarehouseLocation' => new Definition('Elektra', 'Seed', 'Companies', 'WarehouseLocation'),

        );
    }
}

CompanyDefinitions::initialize();