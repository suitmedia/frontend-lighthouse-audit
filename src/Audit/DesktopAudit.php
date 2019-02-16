<?php

namespace Suitmedia\LighthouseAudit\Audit;

class DesktopAudit extends AbstractAudit
{
    /**
     * Get the default chrome flags.
     *
     * @return array
     */
    protected function getDefaultChromeFlags() :array
    {
        $flags = parent::getDefaultChromeFlags();

        return array_merge($flags, ['--window-size=1440,900']);
    }
}
