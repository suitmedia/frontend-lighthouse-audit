<?php

namespace Suitmedia\LighthouseAudit\Tests\Audit;

use Suitmedia\LighthouseAudit\Audit\MobileAudit;
use Suitmedia\LighthouseAudit\Command;
use Suitmedia\LighthouseAudit\Tests\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Process\Exception\LogicException;

class MobileAuditTests extends TestCase
{
    /**
     * @var MobileAudit
     */
    protected $audit;

    /**
     * Setup test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $command = new Command();

        $this->input = new ArgvInput([
            'lighthouse-audit',
            '--url-prefix="http://localhost:8000/"',
            '--chrome-flags="--one --two "',
            '--performance='.Command::DEFAULT_PERFORMANCE,
            '--best-practices='.Command::DEFAULT_BEST_PRACTICES,
            '--accessibility='.Command::DEFAULT_ACCESSIBILITY,
            '--seo='.Command::DEFAULT_SEO,
            '--pwa='.Command::DEFAULT_PWA,
            dirname(__DIR__, 2),
        ], $command->getDefinition());

        $this->audit = new MobileAudit(
            $this->processBuilder,
            $this->input,
            $this->output,
            'index.html'
        );
    }

    /** @test */
    public function it_can_generate_command_correctly()
    {
        $expected = [
            'lighthouse-ci',
            'http://localhost:8000/index.html',
            '--chrome-flags="--cellular-only --disable-gpu --disable-timeouts-for-profiling --headless --incognito --no-sandbox --one --two --window-size=375,667"',
            '--performance='.Command::DEFAULT_PERFORMANCE,
            '--best-practices='.Command::DEFAULT_BEST_PRACTICES,
            '--accessibility='.Command::DEFAULT_ACCESSIBILITY,
            '--seo='.Command::DEFAULT_SEO,
            '--pwa='.Command::DEFAULT_PWA,
            '--emulated-form-factor=mobile',
            '--throttling-method=devtools',
        ];

        $actual = $this->invokeMethod($this->audit, 'generateCommand');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_run_audit_process_successfully()
    {
        $this->processBuilder->shouldReceive('create')
            ->times(1)
            ->andReturn($this->process);

        $this->process->shouldReceive('run')
            ->times(1)
            ->andReturn(0);

        $this->process->shouldReceive('isSuccessful')
            ->times(1)
            ->andReturn(true);

        $this->output->shouldReceive('writeln')
            ->times(1)
            ->with('[<info>PASS</info>] Processed url: http://localhost:8000/index.html');

        $actual = $this->audit->run();

        $this->assertTrue($actual);
    }

    /** @test */
    public function it_can_run_audit_process_and_handle_process_error()
    {
        $this->processBuilder->shouldReceive('create')
            ->times(1)
            ->andReturn($this->process);

        $this->process->shouldReceive('run')
            ->times(1)
            ->andReturn(0);

        $this->process->shouldReceive('isSuccessful')
            ->times(1)
            ->andReturn(false);

        $this->output->shouldReceive('writeln')
            ->times(1)
            ->with('[<error>FAIL</error>] Processed url: http://localhost:8000/index.html');

        $this->prepareException(LogicException::class);

        $this->audit->run();
    }
}
