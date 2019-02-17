<?php

namespace Suitmedia\LighthouseAudit;

use Suitmedia\LighthouseAudit\Concerns\CanResolveDocumentRoot;
use Suitmedia\LighthouseAudit\Concerns\SanitizeInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Process;

class WebServer
{
    use CanResolveDocumentRoot;
    use SanitizeInput;

    /**
     * Console input interface.
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Current web server process.
     *
     * @var Process
     */
    protected $process;

    /**
     * Process builder instance.
     *
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * WebServer constructor.
     *
     * @param InputInterface $input
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(InputInterface $input, ProcessBuilder $processBuilder)
    {
        $this->input = $input;
        $this->processBuilder = $processBuilder;
    }

    /**
     * Create and run the web server process.
     *
     * @return void
     */
    public function run() :void
    {
        $documentRoot = $this->getDocumentRoot(
            $this->input->getArgument('path')
        );
        $server = $this->getListenAddressAndPort();

        $this->process = $this->processBuilder->create([
            'php',
            '-t',
            $documentRoot,
            '-S',
            $server,
        ]);
        $this->process->setTimeout(0);

        $this->process->start();
    }

    /**
     * Stop the web server.
     *
     * @return void
     */
    public function stop() :void
    {
        if (isset($this->process)) {
            $this->process->stop(1);
            unset($this->process);
        }
    }

    /**
     * Get listen address and port from input.
     *
     * @return string
     */
    protected function getListenAddressAndPort() :string
    {
        $server = $this->input->getOption('server');

        return $this->trimDoubleQuotes(is_string($server) ? $server : Command::DEFAULT_SERVER);
    }

    /**
     * Web server destructor.
     */
    public function __destruct()
    {
        $this->stop();
    }
}
