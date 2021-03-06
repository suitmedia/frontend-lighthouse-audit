<?php

namespace Suitmedia\LighthouseAudit\Tests;

use Suitmedia\LighthouseAudit\Application;
use Suitmedia\LighthouseAudit\Command;
use Suitmedia\LighthouseAudit\ProcessBuilder;
use Symfony\Component\Console\Input\ArgvInput;

class ApplicationTests extends TestCase
{
    /**
     * Console application instance.
     *
     * @var Application
     */
    protected $application;

    /**
     * Setup test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $command = new Command();

        $this->input = new ArgvInput([
            'lighthouse-audit',
            '--except=",_header.php,footer.html,script.php,,"',
            '--server="localhost:8000"',
            '--chrome-flags="--one --two "',
            '--performance='.Command::DEFAULT_PERFORMANCE,
            '--best-practices='.Command::DEFAULT_BEST_PRACTICES,
            '--accessibility='.Command::DEFAULT_ACCESSIBILITY,
            '--seo='.Command::DEFAULT_SEO,
            '--pwa='.Command::DEFAULT_PWA,
            __DIR__.'/Fixtures/frontend',
        ], $command->getDefinition());

        $this->application = new Application();
        $this->application->setProcessBuilder($this->processBuilder);
        $this->application->setAutoExit(false);
    }

    /** @test */
    public function it_can_create_process_builder_instance_automatically()
    {
        $this->application = new Application();
        $this->application->setAutoExit(false);

        $builder = $this->application->getProcessBuilder();

        $this->assertNotNull($builder);
        $this->assertInstanceOf(ProcessBuilder::class, $builder);
    }

    /** @test */
    public function it_can_be_run_successfully()
    {
        $this->output->shouldReceive('writeln');

        $this->processBuilder->shouldReceive('create')
            ->andReturn($this->process);
        $this->process->shouldReceive('setTimeout');
        $this->process->shouldReceive('run')
            ->andReturn(0);
        $this->process->shouldReceive('start');
        $this->process->shouldReceive('isSuccessful')
            ->andReturn(true);

        $actual = $this->application->run($this->input, $this->output);

        $this->assertEquals(0, $actual);
    }
}
