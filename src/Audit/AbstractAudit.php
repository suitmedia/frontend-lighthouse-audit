<?php

namespace Suitmedia\LighthouseAudit\Audit;

use Suitmedia\LighthouseAudit\Audit\Concerns\CanRetrieveInputValues;
use Suitmedia\LighthouseAudit\ProcessBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class AbstractAudit
{
    use CanRetrieveInputValues;

    /**
     * Process builder object.
     *
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Console output interface.
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Current filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Current url.
     *
     * @var string
     */
    protected $url;

    /**
     * AbstractProcess constructor.
     *
     * @param ProcessBuilder  $processBuilder
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $filename
     */
    public function __construct(ProcessBuilder $processBuilder, InputInterface $input, OutputInterface $output, string $filename)
    {
        $this->processBuilder = $processBuilder;
        $this->input = $input;
        $this->output = $output;
        $this->filename = $filename;

        $this->url = $this->getUrlPrefix().$this->filename;
    }

    /**
     * Generate command for the current process.
     *
     * @return array
     */
    protected function generateCommand() :array
    {
        $command = [
            'lighthouse-ci',
            $this->url,
        ];
        $command[] = '--chrome-flags="'.implode(' ', $this->getChromeFlags()).'"';

        return array_merge($command, $this->getCommandOptions());
    }

    /**
     * Get the command options.
     *
     * @return array
     */
    protected function getCommandOptions() :array
    {
        return [
            '--performance='.$this->getPerformanceScore(),
            '--best-practices='.$this->getBestPracticesScore(),
            '--accessibility='.$this->getAccessibilityScore(),
            '--seo='.$this->getSeoScore(),
            '--pwa='.$this->getPwaScore(),
            '--emulated-form-factor='.$this->getMode(),
            '--throttling-method=devtools',
        ];
    }

    /**
     * Get the default chrome flags.
     *
     * @return array
     */
    protected function getDefaultChromeFlags() :array
    {
        return ['--no-sandbox', '--headless', '--disable-gpu'];
    }

    /**
     * Run the process.
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function run() :bool
    {
        $status = '<info>PASS</info>';

        $process = $this->processBuilder->create($this->generateCommand());
        $process->run();
        $processStatus = $process->isSuccessful();

        if (!$processStatus) {
            $status = '<error>FAIL</error>';
        }

        $this->output->writeln(sprintf('[%s] Processed url: %s', $status, $this->url));

        if (!$processStatus) {
            throw new ProcessFailedException($process);
        }

        return $processStatus;
    }
}
