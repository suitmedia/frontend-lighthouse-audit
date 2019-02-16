<?php

namespace Suitmedia\LighthouseAudit\Tests;

use Suitmedia\LighthouseAudit\HtmlFileFinder;

class HtmlFileFinderTests extends TestCase
{
    /** @test */
    public function it_returns_false_when_trying_to_validate_html_file_with_a_non_string_parameter()
    {
        $finder = new HtmlFileFinder(__DIR__.'/Fixtures/frontend');

        $actual = $this->invokeMethod($finder, 'isHtmlFile', [['index.php', 9876]]);

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_returns_false_when_trying_to_validate_html_file_with_an_invalid_extension()
    {
        $finder = new HtmlFileFinder(__DIR__.'/Fixtures/frontend');

        $actual = $this->invokeMethod($finder, 'isHtmlFile', ['script.js']);

        $this->assertFalse($actual);

        $actual = $this->invokeMethod($finder, 'isHtmlFile', ['script.css']);

        $this->assertFalse($actual);
    }

    /** @test */
    public function it_returns_true_when_trying_to_validate_html_file_with_a_valid_extension()
    {
        $finder = new HtmlFileFinder(__DIR__.'/Fixtures/frontend');

        $actual = $this->invokeMethod($finder, 'isHtmlFile', ['script.php']);

        $this->assertTrue($actual);

        $actual = $this->invokeMethod($finder, 'isHtmlFile', ['script.html']);

        $this->assertTrue($actual);
    }

    /** @test */
    public function it_can_find_html_files_correctly()
    {
        $expected = [
            '_header.php',
            'dashboard.php',
            'footer.html',
            'index.html',
            'script.php',
        ];

        $finder = new HtmlFileFinder(__DIR__.'/Fixtures/frontend');

        $actual = $finder->getFiles();

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function it_throws_exception_if_the_given_path_is_not_exists_or_is_not_a_directory()
    {
        $this->prepareException(\LogicException::class);
        new HtmlFileFinder(__DIR__.'Fixtures/frontend');

        $this->prepareException(\LogicException::class);
        new HtmlFileFinder(__DIR__.'/Fixtures/frontend/dashboard.php');
    }

    /** @test */
    public function it_returns_empty_array_when_scanning_an_invalid_directory()
    {
        $expected = [];
        $finder = new HtmlFileFinder(__DIR__.'/Fixtures/frontend');

        $this->setPropertyValue($finder, 'path', __DIR__.'Fixtures/frontend');

        $actual = @$finder->getFiles();

        $this->assertEquals($expected, $actual);
    }
}
