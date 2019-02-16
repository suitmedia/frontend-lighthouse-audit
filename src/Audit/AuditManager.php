<?php

namespace Suitmedia\LighthouseAudit\Audit;

use Suitmedia\LighthouseAudit\Command;
use Suitmedia\LighthouseAudit\ProcessBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AuditManager
{
    /**
     * Audit objects.
     *
     * @var array
     */
    protected $audits = [];

    /**
     * Process builder object.
     *
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Console input interface.
     *
     * @var InputInterface
     */
    protected $input;

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
     * AuditManager constructor.
     *
     * @param ProcessBuilder $processBuilder
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $filename
     */
    public function __construct(ProcessBuilder $processBuilder, InputInterface $input, OutputInterface $output, string $filename)
    {
        $this->processBuilder = $processBuilder;
        $this->input = $input;
        $this->output = $output;
        $this->filename = $filename;
    }

    /**
     * Get the default audit name.
     *
     * @return string
     */
    public function getDefaultAudit() :string
    {
        $mode = $this->input->getOption('mode');

        return is_string($mode) ? $mode : Command::DEFAULT_MODE;
    }

    /**
     * Get an audit instance.
     *
     * @param string|null $audit
     * @return AbstractAudit
     */
    public function audit(string $audit = null) :AbstractAudit
    {
        $audit = $audit ?: $this->getDefaultAudit();

        if (! isset($this->audits[$audit])) {
            $this->audits[$audit] = $this->createAudit($audit);
        }

        return $this->audits[$audit];
    }

    /**
     * Create a new audit instance based on
     * the given audit name.
     *
     * @param string $audit
     * @return AbstractAudit
     */
    protected function createAudit(string $audit) :AbstractAudit
    {
        $method = 'create'.ucfirst($audit).'Audit';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new \InvalidArgumentException("Audit [$audit] is not supported.");
    }

    /**
     * Create a new desktop audit instance.
     *
     * @return AbstractAudit
     */
    protected function createDesktopAudit() :AbstractAudit
    {
        return new DesktopAudit(
            $this->processBuilder,
            $this->input,
            $this->output,
            $this->filename
        );
    }

    /**
     * Create a new mobile audit instance.
     *
     * @return AbstractAudit
     */
    protected function createMobileAudit() :AbstractAudit
    {
        return new MobileAudit(
            $this->processBuilder,
            $this->input,
            $this->output,
            $this->filename
        );
    }

    /**
     * Dynamically call the default audit instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->audit()->$method(...$parameters);
    }
}
