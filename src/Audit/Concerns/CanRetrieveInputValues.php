<?php

namespace Suitmedia\LighthouseAudit\Audit\Concerns;

use Suitmedia\LighthouseAudit\Command;

trait CanRetrieveInputValues
{
    use SanitizeInput;

    /**
     * Console input interface.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * Get analysis emulation mode.
     *
     * @return string
     */
    protected function getMode() :string
    {
        $mode = $this->input->getOption('mode');

        return $this->trimDoubleQuotes(is_string($mode) ? $mode : Command::DEFAULT_MODE);
    }

    /**
     * Get URL prefix from input.
     *
     * @return string
     */
    protected function getUrlPrefix() :string
    {
        $prefix = $this->input->getOption('url-prefix');

        return $this->trimDoubleQuotes(is_string($prefix) ? $prefix : Command::DEFAULT_URL_PREFIX);
    }

    /**
     * Get the chrome flags.
     *
     * @return array
     */
    protected function getChromeFlags() :array
    {
        $flags = $this->input->getOption('chrome-flags');

        $flags = is_string($flags) ? explode(' ', $this->trimDoubleQuotes($flags)) : [];
        $flags = array_filter(array_map('trim', $flags));

        $flags = array_unique(array_merge($this->getDefaultChromeFlags(), $flags));
        sort($flags);

        return $flags;
    }

    /**
     * Get minimal performance score.
     *
     * @return string
     */
    protected function getPerformanceScore() :string
    {
        $score = $this->input->getOption('performance');

        return $this->trimDoubleQuotes(is_string($score) ? $score : Command::DEFAULT_PERFORMANCE);
    }

    /**
     * Get minimal best-practices score.
     *
     * @return string
     */
    protected function getBestPracticesScore() :string
    {
        $score = $this->input->getOption('best-practices');

        return $this->trimDoubleQuotes(is_string($score) ? $score : Command::DEFAULT_BEST_PRACTICES);
    }

    /**
     * Get minimal accessibility score.
     *
     * @return string
     */
    protected function getAccessibilityScore() :string
    {
        $score = $this->input->getOption('accessibility');

        return $this->trimDoubleQuotes(is_string($score) ? $score : Command::DEFAULT_ACCESSIBILITY);
    }

    /**
     * Get minimal SEO score.
     *
     * @return string
     */
    protected function getSeoScore() :string
    {
        $score = $this->input->getOption('seo');

        return $this->trimDoubleQuotes(is_string($score) ? $score : Command::DEFAULT_SEO);
    }

    /**
     * Get minimal PWA score.
     *
     * @return string
     */
    protected function getPwaScore() :string
    {
        $score = $this->input->getOption('pwa');

        return $this->trimDoubleQuotes(is_string($score) ? $score : Command::DEFAULT_PWA);
    }

    /**
     * Get the default chrome flags.
     *
     * @return array
     */
    abstract protected function getDefaultChromeFlags() :array;
}
