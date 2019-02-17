<?php

namespace Suitmedia\LighthouseAudit\Concerns;

use LogicException;

trait CanResolveDocumentRoot
{
    /**
     * Validate and return the real document root path.
     *
     * @param mixed $path
     * @throws LogicException
     * @return string
     */
    public function getDocumentRoot($path) :string
    {
        $path = is_string($path) ? realpath($path) : realpath('.');

        if ($path === false || !is_dir($path)) {
            throw new LogicException('The given path is not a directory or directory is not exists.');
        }

        return $path;
    }
}
