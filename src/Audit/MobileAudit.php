<?php

namespace Suitmedia\LighthouseAudit\Audit;

class MobileAudit extends AbstractAudit
{
    /**
     * Get the default chrome flags.
     *
     * @return array
     */
    protected function getDefaultChromeFlags() :array
    {
        $flags = parent::getDefaultChromeFlags();

        return array_merge($flags, ['--cellular-only', '--window-size=375,667']);
    }
}
