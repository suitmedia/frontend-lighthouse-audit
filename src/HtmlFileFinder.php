<?php

namespace Suitmedia\LighthouseAudit;

use LogicException;
use Suitmedia\LighthouseAudit\Concerns\CanResolveDocumentRoot;

class HtmlFileFinder
{
    use CanResolveDocumentRoot;

    /**
     * Path to find HTML files.
     *
     * @var string
     */
    protected $path;

    /**
     * HtmlFileFinder constructor.
     *
     * @param mixed $path
     * @throws LogicException
     */
    public function __construct($path)
    {
        $this->path = $this->getDocumentRoot($path);
    }

    /**
     * Get the HTML files.
     *
     * @return array
     */
    public function getFiles() :array
    {
        $dir = opendir($this->path);
        $files = [];

        if ($dir === false) {
            return $files;
        }

        while (($file = readdir($dir)) !== false) {
            if (is_dir($this->path.$file) || !$this->isHtmlFile($file)) {
                continue;
            }

            $files[] = $file;
        }

        closedir($dir);

        sort($files);

        return $files;
    }

    /**
     * Check if the given file is an HTML file.
     *
     * @param mixed $path
     *
     * @return bool
     */
    protected function isHtmlFile($path) :bool
    {
        if (!is_string($path)) {
            return false;
        }
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, ['php', 'html']);
    }
}
