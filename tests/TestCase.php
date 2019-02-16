<?php

namespace Suitmedia\LighthouseAudit\Tests;

use PHPUnit\Framework\TestCase as AbstractTestCase;
use Suitmedia\LighthouseAudit\ProcessBuilder;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class TestCase extends AbstractTestCase
{
    /**
     * Mocked input interface.
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Mocked output interface.
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Mocked process instance.
     *
     * @var Process
     */
    protected $process;

    /**
     * Mocked process builder instance.
     *
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Invoke protected / private method of the given object.
     *
     * @param object $object
     * @param string $methodName
     * @param array  $parameters
     *
     * @throws \ReflectionException if the method does not exist.
     *
     * @return mixed
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Get any protected / private property value.
     *
     * @param mixed  $object
     * @param string $propertyName
     *
     * @throws \ReflectionException If no property exists by that name.
     *
     * @return mixed
     */
    public function getPropertyValue($object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Set any protected / private property value.
     *
     * @param mixed  $object
     * @param string $propertyName
     * @param mixed $value
     *
     * @throws \ReflectionException If no property exists by that name.
     *
     * @return void
     */
    public function setPropertyValue($object, $propertyName, $value) :void
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

    /**
     * Prepare to get an exception in a test.
     *
     * @param mixed $exception
     *
     * @return void
     */
    protected function prepareException($exception) :void
    {
        if (method_exists($this, 'expectException')) {
            $this->expectException($exception);
        }

        if (method_exists($this, 'setExpectedException')) {
            $this->setExpectedException($exception);
        }
    }

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->input = \Mockery::mock(ArgvInput::class);
        $this->output = \Mockery::mock(ConsoleOutput::class);
        $this->process = \Mockery::mock(Process::class . '[run,isSuccessful]', ['ls', '-alh']);
        $this->processBuilder = \Mockery::mock(ProcessBuilder::class);
    }
}
