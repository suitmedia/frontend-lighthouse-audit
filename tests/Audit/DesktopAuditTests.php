<?php

namespace Suitmedia\LighthouseAudit\Tests\Audit;

use Suitmedia\LighthouseAudit\Audit\DesktopAudit;
use Suitmedia\LighthouseAudit\Tests\TestCase;

class DesktopAuditTests extends TestCase
{
    /**
     * @var DesktopAudit
     */
    protected $audit;

    /**
     * Setup test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->input->shouldReceive('getOption')
            ->with('server')
            ->times(1)
            ->andReturn(false);

        $this->audit = new DesktopAudit(
            $this->processBuilder,
            $this->input,
            $this->output,
            'index.html'
        );
    }

    /** @test */
    public function it_returns_default_chrome_flags_when_there_is_no_additional_flags()
    {
        $expected = ['--no-sandbox', '--headless', '--disable-gpu', '--incognito', '--disable-timeouts-for-profiling', '--window-size=1440,900'];
        sort($expected);
        $this->input->shouldReceive('getOption')
            ->times(1)
            ->with('chrome-flags')
            ->andReturn('');

        $actual = $this->invokeMethod($this->audit, 'getChromeFlags');

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_combines_default_chrome_flags_and_additional_flags()
    {
        $expected = ['--no-sandbox', '--headless', '--disable-gpu', '--incognito', '--disable-timeouts-for-profiling', '--window-size=1440,900', '--one', '--two'];
        sort($expected);
        $this->input->shouldReceive('getOption')
            ->times(1)
            ->with('chrome-flags')
            ->andReturn('--one --two');

        $actual = $this->invokeMethod($this->audit, 'getChromeFlags');

        $this->assertEquals($expected, $actual);
    }
}
