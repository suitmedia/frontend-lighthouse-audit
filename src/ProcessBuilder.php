<?php

namespace Suitmedia\LighthouseAudit;

use Symfony\Component\Process\Process;

class ProcessBuilder
{
    /**
     * Create a new symfony process object based on
     * the given command strings.
     *
     * @param array $command
     *
     * @return Process
     */
    public function create(array $command) :Process
    {
        $process = new Process($command);
        $process->setTimeout(0);

        return $process;
    }
}
