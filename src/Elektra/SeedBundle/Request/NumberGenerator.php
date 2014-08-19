<?php

namespace Elektra\SeedBundle\Request;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Elektra\SeedBundle\Entity\Requests\Request;

class NumberGenerator
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {

        $this->doctrine = $doctrine;
    }

    public function generate()
    {

        $random        = $this->generateRandomString(6);
        $requestNumber = 'REQ-' . $random;

        $repository = $this->doctrine->getRepository('ElektraSeedBundle:Requests\Request');
        $check      = $repository->findOneBy(array('requestNumber' => $requestNumber));

        if ($check === null) {
            return $requestNumber;
        } else {
            // try again
            return $this->generate();
        }
    }

    private function generateRandomString($length)
    {

//        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters   = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}