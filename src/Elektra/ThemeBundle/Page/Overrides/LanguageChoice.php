<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\ThemeBundle\Page\Overrides;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LanguageChoice
 *
 * @package Elektra\ThemeBundle\Page\Overrides
 *
 * @version 0.1-dev
 */
class LanguageChoice extends LanguageSimple
{

    /**
     * @var int
     */
    protected $number;

    /**
     * @param string          $key
     * @param int|string|null $number
     * @param string          $domain
     */
    public function __construct($key = '', $number = null, $domain = null)
    {

        parent::__construct($key, $domain);

        if (is_int($number)) {
            $this->number = $number;
        } else {
            if ($number == 'plural') {
                $this->setPlural();
            } else {
                $this->setSingular();
            }
        }
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {

        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber()
    {

        return $this->number;
    }

    /**
     *
     */
    public function setPlural()
    {

        $this->number = 2;
    }

    /**
     *
     */
    public function setSingular()
    {

        $this->number = 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(TranslatorInterface $translator)
    {

        $translated = $translator->transChoice($this->key, $this->number, $this->replacements, $this->domain);

        if ($translated == $this->key) {
            return '';
        }

        return $translated;
    }
}