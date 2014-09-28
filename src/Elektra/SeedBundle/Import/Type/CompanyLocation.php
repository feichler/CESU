<?php

namespace Elektra\SeedBundle\Import\Type;

use Elektra\SeedBundle\Import\Type;

class CompanyLocation extends Type
{

    public static function getOrder()
    {

        return 21;
    }

    /**
     * @return void
     */
    protected function initialiseFields()
    {

        /*
         * Company Fields
         */
        // required field - company type (partner or customer)
        $this->addField('compType', 'Company Type', true, array('Type of the Company: Partner Organization or Customer Company', 'P = Partner Organization', 'C = Customer Company'));
        // optional field - company name
        $this->addField('compName', 'Company Name', false, array('Full name of the company', 'required if the company is not yet in the database'));
        // optional field - company alias
        $this->addField('compAlias', 'Company Alias', false, array('Alias / abbreviation / short name of the company', 'required if the company is not yet in the database'));
        // optional field - organization type (required for partners)
        $this->addField(
            'orgType',
            'Organization Type',
            false,
            array(
                'Type of organization',
                'required for Partner Organizations if company not yet in the database',
                'Must match an existing Organization Type alias or name'
            )
        );
        // optional field - associated partner
        $this->addField(
            'partner',
            'Associated Partner',
            false,
            array(
                'The Partner Organization who is associated to this Customer Company',
                'required for Customer Companies if company not yet in the database',
                'Must match an existing Partner Organization alias or name'
            )
        );

        /*
         * Company Location Fields
         */
        // optional field - location alias
        $this->addField(
            'locationAlias',
            'Location Alias',
            false,
            array('Internal alias name of the location', 'If not set, a default alias will be generated', 'Default format: Location [country code] [incremented value]')
        );

        // required field - country
        $this->addField('country', 'Country', true, array('Two-letter country code (according to ISO-3166 Alpha-2)'));
        // optional field - state
        $this->addField('state', 'State', false, array('State / Province.', 'If abbreviations are used, try to use them always to have consistent data'));
        // required field - city
        $this->addField('city', 'City', true, array());
        // required field - postal code
        $this->addField('postal', 'Postal Code', true, array());
        // required field - address1
        $this->addField('street1', 'Address Line 1', true, array());
        // optional field - address2
        $this->addField('street2', 'Address Line 2', false, array('additional optional address line'));
        // optional field - address3
        $this->addField('street3', 'Address Line 3', false, array('additional optional address line'));
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {

        return 'company_locations';
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return 'Company Locations';
    }
}
