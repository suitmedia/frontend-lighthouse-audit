<?php

namespace Suitmedia\LighthouseAudit\Tests\Audit\Concerns;

use Suitmedia\LighthouseAudit\Audit\AbstractAudit;
use Suitmedia\LighthouseAudit\Audit\MobileAudit;
use Suitmedia\LighthouseAudit\Command;
use Suitmedia\LighthouseAudit\Tests\TestCase;

class CanRetrieveInputValuesTests extends TestCase
{
    /**
     * Audit instance.
     *
     * @var AbstractAudit
     */
    protected $audit;

    /**
     * methods to test.
     *
     * @var array
     */
    protected static $methods = [
        'getMode',
        'getUrlPrefix',
        'getPerformanceScore',
        'getBestPracticesScore',
        'getAccessibilityScore',
        'getSeoScore',
        'getPwaScore',
    ];

    /**
     * input keys to test.
     *
     * @var array
     */
    protected static $keys = [
        'mode',
        'url-prefix',
        'performance',
        'best-practices',
        'accessibility',
        'seo',
        'pwa',
    ];

    /**
     * input values to test.
     *
     * @var array
     */
    protected static $values = [
        'desktop',
        'https://google.com/',
        '95',
        '94',
        '96',
        '93',
        '97',
        '99',
    ];

    /**
     * Default values to test.
     *
     * @var array
     */
    protected static $defaults = [
        Command::DEFAULT_MODE,
        Command::DEFAULT_URL_PREFIX,
        Command::DEFAULT_PERFORMANCE,
        Command::DEFAULT_BEST_PRACTICES,
        Command::DEFAULT_ACCESSIBILITY,
        Command::DEFAULT_SEO,
        Command::DEFAULT_PWA,
    ];

    /**
     * Setup test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->input->shouldReceive('getOption')
            ->with('url-prefix')
            ->times(1)
            ->andReturn(false);

        $this->audit = new MobileAudit(
            $this->processBuilder,
            $this->input,
            $this->output,
            'index.html'
        );
    }

    /**
     * Dynamically test get default value when
     * there is no console input.
     *
     * @param $method
     * @param $key
     * @param $default
     * @throws \ReflectionException
     */
    protected function dynamicGetDefaultTest($method, $key, $default)
    {
        $this->input->shouldReceive('getOption')
            ->times(1)
            ->with($key)
            ->andReturn(false);

        $actual = $this->invokeMethod($this->audit, $method, [$key]);

        $this->assertEquals($default, $actual);
    }

    /**
     * Dynamically test retrieve value from console input.
     *
     * @param $method
     * @param $key
     * @param $value
     * @throws \ReflectionException
     */
    protected function dynamicRetrieveTest($method, $key, $value)
    {
        $this->input->shouldReceive('getOption')
            ->times(1)
            ->with($key)
            ->andReturn($value);

        $actual = $this->invokeMethod($this->audit, $method, [$key]);

        $this->assertEquals($value, $actual);
    }

    /** @test */
    public function it_can_retrieve_the_input_values()
    {
        $count = count(self::$methods);
        for ($i=0; $i<$count; $i++) {
            $this->dynamicRetrieveTest(self::$methods[$i], self::$keys[$i], self::$values[$i]);
        }
    }

    /** @test */
    public function it_can_return_the_default_values()
    {
        $count = count(self::$methods);
        for ($i=0; $i<$count; $i++) {
            $this->dynamicGetDefaultTest(self::$methods[$i], self::$keys[$i], self::$defaults[$i]);
        }
    }

    /** @test */
    public function it_returns_default_chrome_flags_when_there_is_no_additional_flags()
    {
        $expected = ['--no-sandbox', '--headless', '--disable-gpu', '--cellular-only', '--window-size=375,667'];
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
        $expected = ['--no-sandbox', '--headless', '--disable-gpu', '--cellular-only', '--window-size=375,667', '--one', '--two'];
        $this->input->shouldReceive('getOption')
            ->times(1)
            ->with('chrome-flags')
            ->andReturn('--one --two');

        $actual = $this->invokeMethod($this->audit, 'getChromeFlags');

        $this->assertEquals($expected, $actual);
    }
}
