<?php
/**
 * @author    Florian Eichler <florian@eichler.co.at>
 * @author    Alexander Spengler <alexander.spengler@habanero-it.eu>
 * @copyright 2014 Florian Eichler, Alexander Spengler. All rights reserved.
 * @license   MINOR add a license
 * @version   0.1-dev
 */

namespace Elektra\SiteBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupDataCommand
 *
 * @package Elektra\SiteBundle\Command
 *
 * @version 0.1-dev
 */
class SetupDataCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {

        $this->setName('site:setup:data');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dropSchema          = $this->getApplication()->find('doctrine:schema:drop');
        $dropSchemaArguments = array(
            'command' => 'doctrine:schema:drop',
            '--force' => true,
        );
        $dropSchemaReturn    = $dropSchema->run(new ArrayInput($dropSchemaArguments), $output);
        if ($dropSchemaReturn == 0) {
            $output->writeln('Schema dropped');
        }

        $createSchema          = $this->getApplication()->find('doctrine:schema:create');
        $createSchemaArguments = array(
            'command' => 'doctrine:schema:create',
        );
        $createSchemaReturn    = $createSchema->run(new ArrayInput($createSchemaArguments), $output);
        if ($createSchemaReturn == 0) {
            $output->writeln('Schema created');
        }

        $loadFixtures          = $this->getApplication()->find('doctrine:fixtures:load');
        $loadFixturesArguments = array(
            'command' => 'doctrine:fixtures:load',
        );
        $input                 = new ArrayInput($loadFixturesArguments);
        $input->setInteractive(false);
        $loadFixturesReturn = $loadFixtures->run($input, $output);

        if ($loadFixturesReturn == 0) {
            $output->writeln('Fixtures loaded');
        }
    }
}