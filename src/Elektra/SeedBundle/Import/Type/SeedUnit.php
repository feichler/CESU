<?php

namespace Elektra\SeedBundle\Import\Type;

use Elektra\SeedBundle\Import\Type;

class SeedUnit extends Type
{

    public static function getOrder()
    {

        return 10;
    }

    protected function initialiseFields()
    {

        // required field - serial number
        $this->addField('serial', 'Serial Number', true);
        // required field - model name
        $this->addField('model', 'Model Name', true, array('Model name of the Seed Unit', 'If not matched by an existing model, a new one will be created'));
        // required field - power cord type
        $this->addField('power', 'Power Cord Type', true, array('Power cord type of the Seed Unit', 'If not matched by an existing power cord type, a new one will be created'));
        // required field - warehouse
        $this->addField('warehouse', 'Warehouse', true, array('Warehouse where the Seed Unit is located', 'Must match an existing warehouse alias'));
        // optional date fields
        $this->addField('date', 'Date', false, array('Date when the Seed Unit arrived at the warehouse', 'Defaults to "today"'));
        $this->addField('time', 'Time', false, array('Time when the Seed Unit arrived at the warehouse', 'Defaults to "now"'));
        $this->addField('timezone', 'Timezone (UTC offset)', false, array('Timezone of the Date / Time Columns given as numeric offset to UTC', 'Defaults to +00:00 for UTC'));
    }

    public function getIdentifier()
    {

        return 'seed_units';
    }

    public function getTitle()
    {

        return 'Seed Units';
    }
}