<?php

namespace Suitmedia\LighthouseAudit;

use Symfony\Component\Console\Application as AbstractApplication;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Application extends AbstractApplication
{
    /**
     * Process builder object.
     *
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Application constructor.
     *
     * @param string $name
     * @param string $version
     */
    public function __construct(string $name = 'lighthouse-audit', string $version = '1.0.0')
    {
        parent::__construct($name, $version);
    }

    /**
     * Disable XDebug. This method is copied from:
     * https://github.com/sebastianbergmann/phpcpd/blob/ca6b97f32ebdd3585652a3035d6221a8d2a6c11b/src/CLI/Application.php#L87.
     *
     * @return void
     */
    private function disableXdebug() :void
    {
        if (!\extension_loaded('xdebug')) {
            return;
        }
        \ini_set('xdebug.scream', 0);
        \ini_set('xdebug.max_nesting_level', 8192);
        \ini_set('xdebug.show_exception_trace', 0);
        \ini_set('xdebug.show_error_trace', 0);
        \xdebug_disable();
    }

    /**
     * Run the Lighthouse Audit application.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Throwable
     *
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output) :int
    {
        $this->disableXdebug();

        return parent::doRun($input, $output);
    }

    /**
     * Get Lighthouse Audit command name.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param InputInterface $input
     *
     * @return string
     */
    protected function getCommandName(InputInterface $input) :string
    {
        return $this->getName();
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array
     */
    protected function getDefaultCommands() :array
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new Command($this->getName());

        return $commands;
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition
     */
    protected function getDefaultInputDefinition() :InputDefinition
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
        ]);
    }

    /**
     * Gets the InputDefinition related to this Application.
     * This method is copied from :
     * https://github.com/sebastianbergmann/phpcpd/blob/ca6b97f32ebdd3585652a3035d6221a8d2a6c11b/src/CLI/Application.php#L31.
     *
     * @return InputDefinition
     */
    public function getDefinition() :InputDefinition
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * Get the process builder object.
     *
     * @return ProcessBuilder
     */
    public function getProcessBuilder() :ProcessBuilder
    {
        if ($this->processBuilder === null) {
            $this->processBuilder = new ProcessBuilder();
        }

        return $this->processBuilder;
    }

    /**
     * Set the process builder object.
     *
     * @param ProcessBuilder $builder
     */
    public function setProcessBuilder(ProcessBuilder $builder) :void
    {
        $this->processBuilder = $builder;
    }
}
