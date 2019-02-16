<?php

namespace Suitmedia\LighthouseAudit\Tests\Audit;

use InvalidArgumentException;
use Suitmedia\LighthouseAudit\Audit\AuditManager;
use Suitmedia\LighthouseAudit\Audit\DesktopAudit;
use Suitmedia\LighthouseAudit\Audit\MobileAudit;
use Suitmedia\LighthouseAudit\Tests\TestCase;

class AuditManagerTests extends TestCase
{
    /**
     * Audit manager instance.
     *
     * @var AuditManager
     */
    protected $manager;

    /**
     * Setup test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->manager = new AuditManager(
            $this->processBuilder,
            $this->input,
            $this->output,
            'index.html'
        );
    }

    /** @test */
    public function it_can_resolve_the_default_audit_name()
    {
        $expected = 'tablet';

        $this->input->shouldReceive('getOption')
            ->with('mode')
            ->times(1)
            ->andReturn($expected);

        $actual = $this->manager->getDefaultAudit();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_can_provide_the_default_audit_instance()
    {
        $this->input->shouldReceive('getOption')
            ->with('mode')
            ->times(1)
            ->andReturn('desktop');

        $this->input->shouldReceive('getOption')
            ->with('url-prefix')
            ->times(1)
            ->andReturn('http://localhost:8000/');

        $actual = $this->manager->audit();

        $this->assertInstanceOf(DesktopAudit::class, $actual);
    }

    /** @test */
    public function it_can_provide_a_desktop_audit_instance()
    {
        $this->input->shouldReceive('getOption')
            ->with('url-prefix')
            ->times(1)
            ->andReturn('http://localhost:8000/');

        $actual = $this->manager->audit('desktop');

        $this->assertInstanceOf(DesktopAudit::class, $actual);
    }

    /** @test */
    public function it_can_provide_a_mobile_audit_instance()
    {
        $this->input->shouldReceive('getOption')
            ->with('url-prefix')
            ->times(1)
            ->andReturn('http://localhost:8000/');

        $actual = $this->manager->audit('mobile');

        $this->assertInstanceOf(MobileAudit::class, $actual);
    }

    /** @test */
    public function it_throws_exception_when_trying_to_create_unsupported_audit_instance()
    {
        $this->prepareException(InvalidArgumentException::class);

        $this->invokeMethod($this->manager, 'createAudit', ['tablet']);
    }
}
