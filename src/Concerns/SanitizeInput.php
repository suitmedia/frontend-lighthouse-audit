<?php

namespace Suitmedia\LighthouseAudit\Concerns;

trait SanitizeInput
{
    /**
     * Trim double quotes from input options.
     *
     * @param string $text
     *
     * @return string
     */
    protected function trimDoubleQuotes(string $text) :string
    {
        return ((strpos($text, '"') === 0) && ($text[strlen($text) - 1] === '"')) ?
            substr($text, 1, -1) :
            $text;
    }
}
