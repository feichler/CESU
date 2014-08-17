<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 17.08.14
 * Time: 13:46
 */

namespace Elektra\SeedBundle\Table;


use Elektra\SeedBundle\Entity\Companies\Address;

class TableHelper {

    /**
     * @param Address $address
     * @return string
     */
    public static function renderAddress(Address $address)
    {
        $text = $address->getStreet1();
        if ($address->getStreet2() != null)
        {
            $text = $text . "\r\n" . $address->getStreet2();
        }
        if ($address->getStreet3() != null)
        {
            $text = $text . "\r\n" . $address->getStreet3();
        }
        $text = $text . "\r\n" . $address->getPostalCode() . $address->getCity();

        return $text;
    }
}