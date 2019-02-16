<?php

namespace Suitmedia\LighthouseAudit\Tests;

use Suitmedia\LighthouseAudit\Application;
use Suitmedia\LighthouseAudit\Command;

class CommandTests extends TestCase
{
    /**
     * Console application instance.
     *
     * @var Application
     */
    protected $application;

    /**
     * Console command instance.
     *
     * @var Command
     */
    protected $command;

    /**
     * Setup test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->application = new Application();
        $this->application->setProcessBuilder($this->processBuilder);

        $this->command = new Command();
        $this->command->setApplication($this->application);
    }

    /** @test */
    public function it_can_retrieve_excluded_files_from_console_input()
    {
        $expected = ['index.html', 'home.php'];

        $this->input->shouldReceive('getOption')
            ->with('except')
            ->times(1)
            ->andReturn('index.html,home.php');

        $actual = $this->command->getExcludedFiles($this->input);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_retrieve_excluded_files_from_console_input_with_quotes()
    {
        $expected = ['index.html', 'home.php'];

        $this->input->shouldReceive('getOption')
            ->with('except')
            ->times(1)
            ->andReturn('"index.html,home.php,,"');

        $actual = $this->command->getExcludedFiles($this->input);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_be_executed()
    {
        $this->input->shouldReceive('getOption')
            ->with('url-prefix')
            ->andReturn('http://localhost:8000/');

        $this->input->shouldReceive('getOption')
            ->with('except')
            ->andReturn('",_header.php,footer.html,script.php,,"');

        $this->input->shouldReceive('getArgument')
            ->andReturn(__DIR__.'/Fixtures/frontend');

        $this->input->shouldReceive('getOption')
            ->with('mode')
            ->andReturn('mobile');
        $this->input->shouldReceive('getOption')
            ->with('chrome-flags')
            ->andReturn('');
        $this->input->shouldReceive('getOption')
            ->with('performance')
            ->andReturn('80');
        $this->input->shouldReceive('getOption')
            ->with('best-practices')
            ->andReturn('80');
        $this->input->shouldReceive('getOption')
            ->with('accessibility')
            ->andReturn('80');
        $this->input->shouldReceive('getOption')
            ->with('seo')
            ->andReturn('80');
        $this->input->shouldReceive('getOption')
            ->with('pwa')
            ->andReturn('80');

        $this->output->shouldReceive('writeln');

        $this->processBuilder->shouldReceive('create')
            ->andReturn($this->process);
        $this->process->shouldReceive('run')
            ->andReturn(0);
        $this->process->shouldReceive('isSuccessful')
            ->andReturn(true);

        $actual = $this->invokeMethod($this->command, 'execute', [$this->input, $this->output]);

        $this->assertEquals(0, $actual);
    }
}
