<?php

namespace Elektra\SeedBundle\Import\Type;

use Elektra\SeedBundle\Import\Type;

class Company extends Type
{

    public static function getOrder()
    {

        return 20;
    }

    protected function initialiseFields()
    {

        // required field - company type (partner or customer)
        $this->addField('compType', 'Company Type', true, array('Type of the Company: Partner Organization or Customer Company', 'P = Partner Organization', 'C = Customer Company'));
        // required field - company name
        $this->addField('compName', 'Company Name', true, array('Full name of the company'));
        // required field - company alias
        $this->addField('compAlias', 'Company Alias', true, array('Alias / abbreviation / short name of the company'));
        // optional field - organization type (required for partners)
        $this->addField(
            'orgType',
            'Organization Type',
            false,
            array('Type of organization', 'required for Partner Organizations, ignored for Customer Companies', 'Must match an existing Organization Type alias or name')
        );
        // optional field - associated partner
        $this->addField(
            'partner',
            'Associated Partner',
            false,
            array('The partner who is associated to this customer', 'required for Customer Companies, ignored for Partner Organizations', 'Must match an existing Partner Organization alias or name')
        );
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {

        return 'companies';
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return 'Companies';
    }
}