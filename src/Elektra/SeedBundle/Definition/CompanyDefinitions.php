<?php

namespace Elektra\SeedBundle\Definition;

use Elektra\CrudBundle\Crud\Definition;
use Elektra\SeedBundle\Definition\Companies\AddressDefinition;
use Elektra\SeedBundle\Definition\Companies\AddressTypeDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyLocationDefinition;
use Elektra\SeedBundle\Definition\Companies\CompanyPersonDefinition;
use Elektra\SeedBundle\Definition\Companies\ContactInfoDefinition;
use Elektra\SeedBundle\Definition\Companies\ContactInfoTypeDefinition;
use Elektra\SeedBundle\Definition\Companies\CountryDefinition;
use Elektra\SeedBundle\Definition\Companies\CustomerDefinition;
use Elektra\SeedBundle\Definition\Companies\LocationDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerDefinition;
use Elektra\SeedBundle\Definition\Companies\PartnerTierDefinition;
use Elektra\SeedBundle\Definition\Companies\PersonDefinition;
use Elektra\SeedBundle\Definition\Companies\RegionDefinition;
use Elektra\SeedBundle\Definition\Companies\RequestingCompanyDefinition;
use Elektra\SeedBundle\Definition\Companies\SalesTeamDefinition;
use Elektra\SeedBundle\Definition\Companies\WarehouseLocationDefinition;

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
            'Address'           => new AddressDefinition(),
            'AddressType'       => new AddressTypeDefinition(),
            'CompanyLocation'   => new CompanyLocationDefinition(),
            'CompanyPerson'     => new CompanyPersonDefinition(),
            'ContactInfo'       => new ContactInfoDefinition(),
            'ContactInfoType'   => new ContactInfoTypeDefinition(),
            'Country'           => new CountryDefinition(),
            'Customer'          => new CustomerDefinition(),
            'Location'          => new LocationDefinition(),
            'Partner'           => new PartnerDefinition(),
            'PartnerTier'       => new PartnerTierDefinition(),
            'Person'            => new PersonDefinition(),
            'Region'            => new RegionDefinition(),
            'RequestingCompany' => new RequestingCompanyDefinition(),
            'SalesTeam'         => new SalesTeamDefinition(),
            'WarehouseLocation' => new WarehouseLocationDefinition(),

        );
    }
}

CompanyDefinitions::initialize();