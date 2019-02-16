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
     * @return Process
     */
    public function create(array $command) :Process
    {
        return new Process($command);
    }
}
