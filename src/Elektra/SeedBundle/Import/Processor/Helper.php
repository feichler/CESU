<?php

namespace Elektra\SeedBundle\Import\Processor;

use Doctrine\ORM\EntityRepository;
use Elektra\SeedBundle\Entity\AnnotableInterface;
use Elektra\SeedBundle\Entity\Companies\Address;
use Elektra\SeedBundle\Entity\Companies\CompanyLocation;
use Elektra\SeedBundle\Entity\Companies\CompanyPerson;
use Elektra\SeedBundle\Entity\Companies\ContactInfo;
use Elektra\SeedBundle\Entity\Companies\ContactInfoType;
use Elektra\SeedBundle\Entity\Companies\Country;
use Elektra\SeedBundle\Entity\Companies\Customer;
use Elektra\SeedBundle\Entity\Companies\Partner;
use Elektra\SeedBundle\Entity\Companies\PartnerType;
use Elektra\SeedBundle\Entity\Notes\Note;
use Elektra\SeedBundle\Import\Type;

class Helper
{

    const TYPE_CUSTOMER = 'Customer';

    const TYPE_PARTNER = 'Partner';

    const TYPE_ORGTYPE = 'PartnerType';

    const TYPE_COUNTRY = 'Country';

    const TYPE_COMPLOCATION = 'CompanyLocation';

    const TYPE_CONTACTINFOTYPE = 'ContactInfoType';

    const TYPE_CONTACTINFO = 'ContactInfo';

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {

        $this->type = $type;

        $this->cache = array();

        $this->loadContactInfoTypes();
    }

    /**
     *
     */
    private function loadContactInfoTypes()
    {

        $this->addToCache('ciTypeMail', $this->getEntityByField(self::TYPE_CONTACTINFOTYPE, 'internalName', ContactInfoType::EMAIL));
        $this->addToCache('ciTypePhone', $this->getEntityByField(self::TYPE_CONTACTINFOTYPE, 'internalName', ContactInfoType::PHONE));
        $this->addToCache('ciTypeMobile', $this->getEntityByField(self::TYPE_CONTACTINFOTYPE, 'internalName', ContactInfoType::MOBILE));
        $this->addToCache('ciTypeFax', $this->getEntityByField(self::TYPE_CONTACTINFOTYPE, 'internalName', ContactInfoType::FAX));
    }

    /**
     * @return User
     */
    protected function getUser()
    {

        return $this->type->getCrud()->getService('security.context')->getToken()->getUser();
    }

    /*************************************************************************
     * Cache related & data extraction methods
     *************************************************************************/

    /**
     * @param string $key
     * @param object $object
     */
    public function addToCache($key, $object)
    {

        $this->cache[$key] = $object;
    }

    /**
     * @param string $key
     *
     * @return object|null
     */
    public function getFromCache($key)
    {

        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }

        return null;
    }

    public function getCacheKey($type, $fieldName, $fieldValue, $additional = null)
    {

        if ($additional != null) {
            $additional = serialize($additional);
        } else {
            $additional = 'null';
        }

        if (is_string($fieldValue)) {
            $key = md5($type . '-' . $fieldName . '-' . $fieldValue . '-' . $additional);
        } else {
            $key = md5($type . '-' . $fieldName . '-' . $fieldValue->getName() . '-' . $additional);
        }

        return $key;
    }

    /**
     * @param array  $data
     * @param string $key
     *
     * @return mixed|bool
     */
    public function getValueForKey(array $data, $key)
    {

        if (array_key_exists($key, $data) && $data[$key] != '' && $data[$key] != null) {
            return $data[$key];
        }

        return false;
    }

    /*************************************************************************
     * Database / Doctrine related methods
     *************************************************************************/

    /**
     * @param string       $type
     * @param string|array $fieldName
     * @param string       $fieldValue
     * @param bool         $withCache
     * @param array        $restrict
     *
     * @return null|object
     */
    public function getEntityByField($type, $fieldName, $fieldValue, $withCache = true, $restrict = array())
    {

        $entity = null;

        if (is_array($fieldName)) {
            foreach ($fieldName as $singleField) {
                $entity = $this->getEntityByField($type, $singleField, $fieldValue, $withCache, $restrict);
                if ($entity !== null) {
                    break;
                }
            }
        } else {
            if ($withCache) {
                $key    = $this->getCacheKey($type, $fieldName, $fieldValue, $restrict);
                $entity = $this->getFromCache($key);
            }

            if ($entity === null) {
                $fields     = array($fieldName => $fieldValue);
                $parameters = array_merge($restrict, $fields);
                $repository = $this->getRepositoryForType($type);
                $entity     = $repository->findOneBy($parameters);
                // TODO add to cache if not already present?
            }
        }

        return $entity;
    }


    /*************************************************************************
     * Generic type-related helper methods
     *************************************************************************/

    /**
     * @param string $type
     *
     * @return EntityRepository
     */
    private function getRepositoryForType($type)
    {

        $definition = $this->getDefinitionForType($type);
        $repository = $this->type->getCrud()->getService('doctrine')->getRepository($definition->getClassRepository());

        return $repository;
    }

    /**
     * @param string $type
     *
     * @return \Elektra\CrudBundle\Crud\Definition
     */
    private function getDefinitionForType($type)
    {

        switch ($type) {
            case self::TYPE_CUSTOMER:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Customer');
                break;
            case self::TYPE_PARTNER:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Partner');
                break;
            case self::TYPE_ORGTYPE:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'PartnerType');
                break;
            case self::TYPE_COUNTRY:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'Country');
                break;
            case self::TYPE_COMPLOCATION:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'CompanyLocation');
                break;
            case self::TYPE_CONTACTINFOTYPE:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfoType');
                break;
            case self::TYPE_CONTACTINFO:
                $definition = $this->type->getCrud()->getDefinition('Elektra', 'Seed', 'Companies', 'ContactInfo');
                break;
        }

        return $definition;
    }

    /*************************************************************************
     * Data methods: Company (Customer & Partner)
     *************************************************************************/

    public function checkCreateCompany(array &$data, $row, $direct = false)
    {

        $valid = true;

        /*
         * Requirements:
         *  common:
         *      - name -> not in database for type
         *      - alias -> not in database for type
         *  specific:
         *      - partner
         *          - orgType -> must be found in database
         *      - customer
         *          - partner -> must be found in database
         */

        // Extract all possible values
        $companyType  = $this->getValueForKey($data, 'compType');
        $companyName  = $this->getValueForKey($data, 'compName');
        $companyAlias = $this->getValueForKey($data, 'compAlias');

        /**
         * Find and check the company type
         */
        if ($companyType == 'C') {
            // checking a customer
            $companyType = self::TYPE_CUSTOMER;
        } else if ($companyType == 'P') {
            // checking a partner
            $companyType = self::TYPE_PARTNER;
        } else {
            // invalid company type
            $this->type->addErrorMessage('Invalid Company Type "' . $companyType . '" found');

            // directly return as further checks are not possible without a valid company type
            return false;
        }

        /**
         * Check the common data fields
         */
        // common: check if the alias is in the database
        if ($companyAlias === false) {
            // alias not set -> error
            if (!$direct) {
                $this->type->addErrorMessage('Company Alias field is required');
            }
            $valid = false;
        } else if ($this->getEntityByField($companyType, 'shortName', $companyAlias, true) !== null) {
            // alias found in the database -> invalid data
            $this->type->addErrorMessage('Company Alias "' . $companyAlias . '" already found in the database');
            $valid = false;
        }

        // common: check if the name is in the database
        if ($companyName === false) {
            if (!$direct) {
                $this->type->addErrorMessage('Company Name is required');
            }
            $valid = false;
        } else if ($this->getEntityByField($companyType, 'name', $companyName, true) !== null) {
            // name found in the database -> invalid data
            $this->type->addErrorMessage('Company Name "' . $companyName . '" already found in the database');
            $valid = false;
        }

        /**
         * Check the specific data fields
         */
        if ($companyType == self::TYPE_CUSTOMER) {
            // specific: check and get the partner
            $partner = $this->loadPartner($data, $row);
            if ($partner === null) {
                $valid = false;
            }
            $data['partner'] = $partner;
        } else {
            // specific: check and get the partner type
            $partnerType = $this->loadPartnerType($data, $row);
            if ($partnerType === null) {
                $valid = false;
            }
            $data['orgType'] = $partnerType;
        }

        return $valid;
    }

    public function createCompany(array &$data, $row)
    {

        /*
         * Find and the company type and create the appropriate instance
         */
        if ($data['compType'] == 'C') {
            $companyType = self::TYPE_CUSTOMER;
            $company     = new Customer();
        } else {
            $companyType = self::TYPE_PARTNER;
            $company     = new Partner();
        }

        /*
         * Set the common data fields
         */
        // name & alias / short name
        $company->setName($data['compName']);
        $company->setShortName($data['compAlias']);

        /*
         * Set the specific data fields
         */
        if ($company instanceof Customer) {
            // partner
            $company->getPartners()->add($data['partner']);
            $data['partner']->getCustomers()->add($company);
        } else if ($company instanceof Partner) {
            // partner type
            $company->setPartnerType($data['orgType']);
        }

        // "created from import" note
        $this->addCreatedNote($company, $row);

        // persist the company
        $manager = $this->type->getCrud()->getService('doctrine')->getManager();
        $manager->persist($company);

        // common note & message
        $this->type->getProcessor()->addNote('Stored Company', 'Stored the company "' . $data['compName'] . '" from row ' . $row);
        $this->type->addSuccessMessage('Successfully stored the company "' . $data['compName'] . '"');

        /*
         * add the company to the cache array
         */
        $this->addToCache($this->getCacheKey($companyType, 'name', $data['compName']), $company);
        $this->addToCache($this->getCacheKey($companyType, 'shortName', $data['compAlias']), $company);

        $data['company'] = $company;

        return true;
    }

    public function loadCompany(array &$data, $row)
    {

        // Extract all possible values
        $companyType  = $this->getValueForKey($data, 'compType');
        $companyName  = $this->getValueForKey($data, 'compName');
        $companyAlias = $this->getValueForKey($data, 'compAlias');

        /**
         * Find and check the company type
         */
        if ($companyType == 'C') {
            // checking a customer
            $companyType = self::TYPE_CUSTOMER;
        } else if ($companyType == 'P') {
            // checking a partner
            $companyType = self::TYPE_PARTNER;
        } else {
            // invalid company type
            $this->type->addErrorMessage('Invalid Company Type "' . $companyType . '" found');

            // directly return as further checks are not possible without a valid company type
            return false;
        }

        /*
         * Check the fields - either name or alias must be given
         */
        if ($companyAlias === false && $companyName === false) {
            $this->type->addErrorMessage('Neither Company Alias nor Company Name given');

            // directly return as further checks are not possible without a valid name / alias
            return false;
        }

        /*
         * Try to load the company
         */
        $company = null;
        if ($companyAlias !== false) {
            $company = $this->getEntityByField($companyType, 'shortName', $companyAlias, true);
        }
        if ($company === null && $companyName !== false) {
            $company = $this->getEntityByField($companyType, 'name', $companyName, true);
        }

        if ($company === null) {
            // company not found in database
            return null;
        } else {
            // company found in database -> check if it is the same (name / alias / specific match)
            $valid = true;

            // alias check
            if ($companyAlias !== false && $company->getShortName() != $companyAlias) {
                $this->type->addErrorMessage('Found Company Alias does not match - Given: "' . $companyAlias . '" Database: "' . $company->getShortName() . '"');
                $valid = false;
            }

            // name check
            if ($companyName !== false && $company->getName() != $companyName) {
                $this->type->addErrorMessage('Found Company Name does not match - Given: "' . $companyName . '" Database: "' . $company->getName() . '"');
                $valid = false;
            }

            if ($companyType == self::TYPE_CUSTOMER) {
                // partner check
                $partner = $this->getValueForKey($data, 'partner');
                if ($partner !== false) {
                    $dbPartner = $company->getPartners()->first();
                    if ($dbPartner->getShortName() != $partner && $dbPartner->getName() != $partner) {
                        $this->type->addErrorMessage(
                            'Found Associated Partner does not match - Given: "' . $partner . '" Database: "' . $dbPartner->getShortName() . '"/"' . $dbPartner->getName() . '"'
                        );
                        $valid = false;
                    }
                }
            } else {
                // partner type check
                $partnerType = $this->getValueForKey($data, 'orgType');
                if ($partnerType !== false) {
                    $dbType = $company->getPartnerType();
                    if ($dbType->getAlias() != $partnerType && $dbType->getName() != $partnerType) {
                        $this->type->addErrorMessage(
                            'Found Organization Type does not match - Given: "' . $partnerType . '" Database: "' . $dbType->getAlias() . '"/"' . $dbType->getName() . '"'
                        );
                        $valid = false;
                    }
                }
            }

            if (!$valid) {
                return false;
            }
        }

        $data['company'] = $company;

        return $company;
    }

    /*************************************************************************
     * Data methods: Company Location
     *************************************************************************/

    public function checkCreateCompanyLocation(array &$data, $row)
    {

        $valid = true;

        /*
         * Requirements:
         *  - location alias -> if given valid within company
         *  - country -> must be found in database
         *  - state -> nothing
         *  - city -> must be given
         *  - postal code -> must be given
         *  - address line 1 -> must be given
         *  - address line 2 -> nothing
         *  - address line 2 -> nothing
         */
        $locationAlias = $this->getValueForKey($data, 'locationAlias');
        // country gets loaded through entity
        $state      = $this->getValueForKey($data, 'state');
        $city       = $this->getValueForKey($data, 'city');
        $postalCode = $this->getValueForKey($data, 'postal');
        $street1    = $this->getValueForKey($data, 'street1');
        $street2    = $this->getValueForKey($data, 'street2');
        $street3    = $this->getValueForKey($data, 'street3');

        $company = $this->getValueForKey($data, 'company');

        /*
         * check the country first, as it is used for generating the alias
         */
        $country = $this->loadCountry($data, $row);
        if ($country === null) {
            $valid = false;
        }
        $data['country'] = $country;

        /*
         * check the location alias
         */
        if ($locationAlias !== false) {
            // if given, verify it is not yet used
            foreach ($company->getLocations() as $location) {
                if ($location->getShortName() == $locationAlias) {
                    $this->type->addErrorMessage('Location Alias "' . $locationAlias . '" already found in the database for this Company');
                    $valid = false;
                }
            }
        } else {
            // if not given, generate it
            if ($country !== null) {
                $prefix = 'Location ' . $country->getAlphaTwo();
                $count  = 1;
                foreach ($company->getLocations() as $location) {
                    if (strpos($location->getShortName(), $prefix) !== false) {
                        $count++;
                    }
                }

                $locationAlias         = $prefix . ' ' . str_pad($count, 2, '0', STR_PAD_LEFT);
                $data['locationAlias'] = $locationAlias;
            } else {
                // if country is invalid, the alias cannot be generated
            }
        }

        /*
         * state
         */
        if ($state === false) {
            $data['state'] = null;
        }

        /*
         * city
         */
        if ($city === false) {
            $this->type->addErrorMessage('City field is required for Company Location');
            $valid = false;
        }

        /*
         * postalCode
         */
        if ($postalCode === false) {
            $this->type->addErrorMessage('Postal Code field is required for Company Location' );
            $valid = false;
        }

        /*
         * street1
         */
        if ($street1 === false) {
            $this->type->addErrorMessage('Address Line 1 field is required for Company Location' );
            $valid = false;
        }

        /*
         * street2
         */
        if ($street2 === false) {
            $data['street2'] = null;
        }

        /*
         * street3
         */
        if ($street3 === false) {
            $data['street3'] = null;
        }

        return $valid;
    }

    public function createCompanyLocation(array &$data, $row)
    {

        $location = new CompanyLocation();
        $location->setShortName($data['locationAlias']);

        $address = new Address();
        $address->setCountry($data['country']);
        $address->setState($data['state']);
        $address->setCity($data['city']);
        $address->setPostalCode($data['postal']);
        $address->setStreet1($data['street1']);
        $address->setStreet2($data['street2']);
        $address->setStreet3($data['street3']);

        $location->setAddress($address);

        // TODO add field for primary
        $location->setIsPrimary(false);

        $this->addCreatedNote($location, $row);

        $data['company']->getLocations()->add($location);
        $location->setCompany($data['company']);

        $manager = $this->type->getCrud()->getService('doctrine')->getManager();
        $manager->persist($location);

        // common note & message
        $this->type->getProcessor()->addNote('Stored Company Location', 'Stored the company location "' . $data['locationAlias'] . '" from row ' . $row);
        $this->type->addSuccessMessage('Successfully stored the company location "' . $data['locationAlias'] . '"');

        /*
         * add the company location to the cache array
         */
        $this->addToCache($this->getCacheKey(self::TYPE_COMPLOCATION, 'shortName', $data['locationAlias']), $location, array('company' => $data['company']));

        $data['location'] = $location;

        return true;
    }

    public function loadCompanyLocation(array &$data, $row)
    {

        // Extract all possible values
        $locationAlias = $this->getValueForKey($data, 'locationAlias');
        // country gets loaded through entity
        $state      = $this->getValueForKey($data, 'state');
        $city       = $this->getValueForKey($data, 'city');
        $postalCode = $this->getValueForKey($data, 'postal');
        $street1    = $this->getValueForKey($data, 'street1');
        $street2    = $this->getValueForKey($data, 'street2');
        $street3    = $this->getValueForKey($data, 'street3');

        /*
         * Find and the check the country
         */
        $country = $this->loadCountry($data, $row);
        if ($country === null) {
            return false;
        }
        $data['country'] = $country;

        /*
         * Check the fields - if an alias is given, try to load
         */
        $location = null;
        if ($locationAlias !== false) {
            $location = $this->getEntityByField(self::TYPE_COMPLOCATION, 'shortName', $locationAlias, true, array('company' => $data['company']));
        }

        if ($location === null) {
            // location not found by alias -> return
            return null;
        } else {
            // location found in database -> check if it is the same
            $valid = true;

            // NOTE: isPrimary -> not yet used
            // NOTE: addressType -> not used

            /*
             * Address
             */
            $address = $location->getAddress();

            // CHECK country should be checked by loading?

            // state
            if ($state !== false && $address->getState() != $state) {
                $this->type->addErrorMessage('Found State does not match - Given: "' . $state . '" Database: "' . $address->getState() . '"');
                $valid = false;
            }

            // city
            if ($city !== false && $address->getCity() != $city) {
                $this->type->addErrorMessage('Found City does not match - Given: "' . $city . '" Database: "' . $address->getCity() . '"');
                $valid = false;
            }

            // postal
            if ($postalCode !== false && $address->getPostalCode() != $postalCode) {
                $this->type->addErrorMessage('Found Postal Code does not match - Given: "' . $postalCode . '" Database: "' . $address->getPostalCode() . '"');
                $valid = false;
            }

            // street1
            if ($street1 !== false && $address->getStreet1() != $street1) {
                $this->type->addErrorMessage('Found Address Line 1 does not match - Given: "' . $street1 . '" Database: "' . $address->getStreet1() . '"');
                $valid = false;
            }

            // street2
            if ($street2 !== false && $address->getStreet2() != $street2) {
                $this->type->addErrorMessage('Found Address Line 2 does not match - Given: "' . $street2 . '" Database: "' . $address->getStreet2() . '"');
                $valid = false;
            }

            // street3
            if ($street3 !== false && $address->getStreet3() != $street3) {
                $this->type->addErrorMessage('Found Address Line 3 does not match - Given: "' . $street3 . '" Database: "' . $address->getStreet3() . '"');
                $valid = false;
            }

            if (!$valid) {
                return false;
            }
        }

        $data['location'] = $location;

        return $location;
    }

    /*************************************************************************
     * Data methods: Company Person
     *************************************************************************/

    public function checkCreateCompanyPerson(array &$data, $row)
    {

        $valid = true;

        /*
         * Requirements:
         *  - salutation - nothing
         *  - first name - must be given
         *  - last name - must be given
         *  - jobTitle - nothing
         *  - email - must be given and not in database (global)
         *  - phone - nothing
         *  - mobile - nothing
         *  - fax - nothing
         *  - combined: first name / last name combination ? not sure
         */

        $salutation = $this->getValueForKey($data, 'salutation');
        $first      = $this->getValueForKey($data, 'first');
        $last       = $this->getValueForKey($data, 'last');
        $jobTitle   = $this->getValueForKey($data, 'jobTitle');
        $email      = $this->getValueForKey($data, 'email');
        $phone      = $this->getValueForKey($data, 'phone');
        $mobile     = $this->getValueForKey($data, 'mobile');
        $fax        = $this->getValueForKey($data, 'fax');

        /*
         * salutation
         */
        if ($salutation === false) {
            $data['salutation'] = null;
        }

        /*
         * first
         */
        if ($first === false) {
            $this->type->addErrorMessage('First Name field is required for Company Location');
            $valid = false;
        }

        /*
         * last
         */
        if ($last === false) {
            $this->type->addErrorMessage('Last Name field is required for Company Location ');
            $valid = false;
        }

        /*
         * jobTitle
         */
        if ($jobTitle === false) {
            $data['jobTitle'] = null;
        }

        /*
         * email
         */
        if ($email === false) {
            $this->type->addErrorMessage('Primary Email field is required for Company Location' );
            $valid = false;
        } else {
            $ciType    = $this->getFromCache('ciTypeMail');
            $mailCheck = $this->getEntityByField(self::TYPE_CONTACTINFO, 'text', $email, true, array('contactInfoType' => $ciType));
            if ($mailCheck !== null) {
                $this->type->addErrorMessage('Primary Email "' . $email . '" is already stored in the database' );
                $valid = false;
            }
        }

        /*
         * phone
         */
        if ($phone === false) {
            $data['phone'] = null;
        }

        /*
         * mobile
         */
        if ($mobile === false) {
            $data['mobile'] = null;
        }

        /*
         * fax
         */
        if ($fax === false) {
            $data['fax'] = null;
        }

        return $valid;
    }

    public function createCompanyPerson(array &$data, $row)
    {

        $person = new CompanyPerson();
        $person->setFirstName($data['first']);
        $person->setLastName($data['last']);
        $person->setSalutation($data['salutation']);
        $person->setJobTitle($data['jobTitle']);

        // TODO add field for primary
        $person->setIsPrimary(false);

        $this->addCreatedNote($person, $row);

        $person->setLocation($data['location']);
        $data['location']->getPersons()->add($person);

        $email = new ContactInfo();
        $email->setContactInfoType($this->getFromCache('ciTypeMail'));
        $email->setName('Primary');
        $email->setText($data['email']);

        $email->setPerson($person);
        $person->getContactInfo()->add($email);

        $this->addToCache($this->getCacheKey(self::TYPE_CONTACTINFO, 'text', $data['email'], array('contactInfoType' => $this->getFromCache('ciTypeMail'))), $email);

        if ($data['phone'] !== null) {
            $phone = new ContactInfo();
            $phone->setContactInfoType($this->getFromCache('ciTypePhone'));
            $phone->setName('Primary');
            $phone->setText($data['phone']);

            $phone->setPerson($person);
            $person->getContactInfo()->add($phone);
        }

        if ($data['mobile'] !== null) {
            $mobile = new ContactInfo();
            $phone->setContactInfoType($this->getFromCache('ciTypeMobile'));
            $mobile->setName('Primary');
            $mobile->setText($data['mobile']);

            $mobile->setPerson($person);
            $person->getContactInfo()->add($mobile);
        }

        if ($data['fax'] !== null) {
            $fax = new ContactInfo();
            $fax->setContactInfoType($this->getFromCache('ciTypeFax'));
            $fax->setName('Primary');
            $fax->setText($data['fax']);

            $fax->setPerson($person);
            $person->getContactInfo()->add($fax);
        }

        $manager = $this->type->getCrud()->getService('doctrine')->getManager();
        $manager->persist($person);

        // common note & message
        $this->type->getProcessor()->addNote('Stored Company Person', 'Stored the company person "' . $data['last'] . ', ' . $data['first'] . '"' );
        $this->type->addSuccessMessage('Successfully stored the company person "' . $data['last'] . ', ' . $data['first'] . '"' );

        $data['person'] = $person;

        return true;
    }

    /*************************************************************************
     * Data methods: internal helpers
     *************************************************************************/

    /**
     * @param array $data
     * @param int   $row
     *
     * @return null|Partner
     */
    private function loadPartner(array &$data, $row)
    {

        $partner = $this->getValueForKey($data, 'partner');

        if ($partner === false) {
            // field not found or set in data -> error
            $this->type->addErrorMessage('Associated Partner field is required for Customer Company' );

            return null;
        }

        $entity = $this->getEntityByField(self::TYPE_PARTNER, array('shortName', 'name'), $partner, true);
        if ($entity === null) {
            // partner not found in database -> error
            $this->type->addErrorMessage('Partner "' . $partner . '" not found in database for Customer Company' );

            return null;
        }

        return $entity;
    }

    /**
     * @param array $data
     * @param int   $row
     *
     * @return null|PartnerType
     */
    private function loadPartnerType(array &$data, $row)
    {

        $partnerType = $this->getValueForKey($data, 'orgType');

        if ($partnerType === false) {
            // field not found or set in data -> error
            $this->type->addErrorMessage('Organization Type field is required for Partner Organization' );

            return null;
        }

        $entity = $this->getEntityByField(self::TYPE_ORGTYPE, array('alias', 'name'), $partnerType, true);
        if ($entity === null) {
            // partner type not found in database -> error
            $this->type->addErrorMessage('Organization Type "' . $partnerType . '" not found in database for Partner Organization' );

            return null;
        }

        return $entity;
    }

    private function loadCountry(array &$data, $row)
    {

        $country = $this->getValueForKey($data, 'country');
        if ($country instanceof Country) {
            return $country;
        }

        if ($country === false) {
            // field not found or set in data -> error
            $this->type->addErrorMessage('Country field is required for Company Location' );

            return null;
        }

        $entity = $this->getEntityByField(self::TYPE_COUNTRY, array('alphaTwo', 'alphaThree'), $country, true);
        if ($entity === null) {
            // country not found in database -> error
            $this->type->addErrorMessage('Country "' . $country . '" not found in database for Company Location' );

            return null;
        }

        return $entity;
    }

    /*************************************************************************
     * Data methods: Commons
     *************************************************************************/

    /**
     * @param AnnotableInterface $entity
     * @param int                $row
     */
    private function addCreatedNote(AnnotableInterface $entity, $row)
    {

        $note = new Note();
        $note->setTitle('Created from import');
        $note->setText('Imported from file "' . $this->type->getProcessor()->getImportEntity()->getUploadFile()->getClientOriginalName() . '" - row ' . $row);
        $note->setTimestamp(time());
        $note->setUser($this->getUser());

        $entity->getNotes()->add($note);
    }
}