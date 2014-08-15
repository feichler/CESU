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
 * Class LanguageChain
 *
 * @package Elektra\ThemeBundle\Page\Overrides
 *
 * @version 0.1-dev
 */
class LanguageChain extends Language
{

    /**
     * @var array
     */
    private $chain;

    /**
     *
     */
    public function __construct()
    {

        parent::__construct();
        $this->chain = array();
    }

    /**
     * @param Language $option
     */
    public function addOption(Language $option)
    {

        $this->chain[] = $option;
    }

    /**
     * @return array
     */
    public function getChain()
    {

        return $this->chain;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation(TranslatorInterface $translator)
    {

        foreach ($this->chain as $i => $option) {
            if ($option instanceof Language) {
                $translated = $option->getTranslation($translator);
                $key        = $option->getKey();
                if ($key != $translated) {
                    return $translated;
                }
            }
        }

        return '';
    }
}