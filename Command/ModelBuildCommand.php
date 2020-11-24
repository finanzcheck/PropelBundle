<?php

/**
 * This file is part of the PropelBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\Bundle\PropelBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

use Propel\Generator\Command\ModelBuildCommand as BaseModelBuildCommand;

/**
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class ModelBuildCommand extends WrappedCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('propel:model:build')
            ->setDescription('Build the model classes based on Propel XML schemas')

            ->addOption('connection', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'Connection to use. Example: default, bookstore')
            ->addOption('schema-dir', null, InputOption::VALUE_REQUIRED, 'The directory where the schema files are placed')
            ->addOption('output-dir', null, InputOption::VALUE_REQUIRED, 'The output directory')
            ->addArgument('bundle', InputArgument::OPTIONAL, 'The bundle to generate model classes from')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function createSubCommandInstance()
    {
        return new BaseModelBuildCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getSubCommandArguments(InputInterface $input)
    {
        $outputDirDefault = $this->getApplication()->getKernel()->getRootDir().'/../';
        $outputDir = $this->input->getOption('output-dir') ?: $outputDirDefault;

        return array(
            '--output-dir' => $outputDir,
            '--schema-dir' => $input->getOption('schema-dir'),
        );
    }
}
