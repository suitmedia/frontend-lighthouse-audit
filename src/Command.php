<?php

namespace Suitmedia\LighthouseAudit;

use LogicException;
use Suitmedia\LighthouseAudit\Audit\AuditManager;
use Suitmedia\LighthouseAudit\Audit\Concerns\SanitizeInput;
use Symfony\Component\Console\Command\Command as AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class Command extends AbstractCommand
{
    use SanitizeInput;

    public const DEFAULT_URL_PREFIX = 'http://localhost:8000/';
    public const DEFAULT_MODE = 'mobile';
    public const DEFAULT_PERFORMANCE = '80';
    public const DEFAULT_BEST_PRACTICES = '80';
    public const DEFAULT_ACCESSIBILITY = '80';
    public const DEFAULT_SEO = '80';
    public const DEFAULT_PWA = '0';

    /**
     * Configures the lighthouse-audit command.
     *
     * @return void
     */
    protected function configure() :void
    {
        $this->setName('lighthouse-audit')
            ->setDefinition([new InputArgument(
                'path',
                InputArgument::REQUIRED,
                'Specify the path of a directory to analyse.'
            )])
            ->addOption(
                'url-prefix',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the url prefix that should be used when testing.',
                self::DEFAULT_URL_PREFIX
            )
            ->addOption(
                'mode',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the mode to run Lighthouse audit. Option: mobile,desktop',
                self::DEFAULT_MODE
            )
            ->addOption(
                'performance',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the minimal performance score for audit to pass',
                self::DEFAULT_PERFORMANCE
            )
            ->addOption(
                'best-practices',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the minimal best-practices score for audit to pass',
                self::DEFAULT_BEST_PRACTICES
            )
            ->addOption(
                'accessibility',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the minimal accessibility score for audit to pass',
                self::DEFAULT_ACCESSIBILITY
            )
            ->addOption(
                'seo',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the minimal seo score for audit to pass',
                self::DEFAULT_SEO
            )
            ->addOption(
                'pwa',
                null,
                InputOption::VALUE_OPTIONAL,
                'Define the minimal pwa score for audit to pass',
                self::DEFAULT_PWA
            )
            ->addOption(
                'except',
                null,
                InputOption::VALUE_OPTIONAL,
                'Provide a list of filenames that you wish to exclude, separated by commas.'.PHP_EOL
            )
            ->addOption(
                'chrome-flags',
                null,
                InputOption::VALUE_OPTIONAL,
                'Custom flags to pass to Chrome (space-delimited). For a full list of flags, '.PHP_EOL.'see http://peter.sh/experiments/chromium-command-line-switches/.'.PHP_EOL
            );
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws LogicException|\Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $app = $this->getApplication();
        $excludedFiles = $this->getExcludedFiles($input);

        $output->writeln($app->getLongVersion());
        $output->writeln('');

        $finder = new HtmlFileFinder($input->getArgument('path'));
        $files = $finder->getFiles();

        foreach ($files as $file) {
            if (in_array($file, $excludedFiles, true)) {
                continue;
            }

            $audit = new AuditManager(
                $this->getApplication()->getProcessBuilder(),
                $input,
                $output,
                $file
            );
            $audit->run();
        }

        return 0;
    }

    /**
     * Get the list of files that should be excluded from analysis.
     *
     * @param InputInterface $input
     *
     * @return array
     */
    public function getExcludedFiles(InputInterface $input) :array
    {
        $except = $input->getOption('except');

        $files = is_string($except) ? explode(',', $this->trimDoubleQuotes($except)) : [];

        return array_filter(array_map('trim', $files));
    }
}
