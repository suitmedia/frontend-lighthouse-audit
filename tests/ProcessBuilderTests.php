<?php

namespace Suitmedia\LighthouseAudit\Tests;

use Suitmedia\LighthouseAudit\ProcessBuilder;
use Symfony\Component\Process\Process;

class ProcessBuilderTests extends TestCase
{
    /** @test */
    public function it_can_create_symfony_process_instance_based_on_the_given_command()
    {
        $builder = new ProcessBuilder();
        $process = $builder->create(['ls', '-alh']);

        $this->assertInstanceOf(Process::class, $process);
        $this->assertEquals('\'ls\' \'-alh\'', $process->getCommandLine());
        $this->assertEquals(0, $process->getTimeout());
    }
}
