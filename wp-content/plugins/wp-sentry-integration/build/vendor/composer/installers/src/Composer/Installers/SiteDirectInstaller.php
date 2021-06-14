<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class SiteDirectInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('module' => 'modules/{$vendor}/{$name}/', 'plugin' => 'plugins/{$vendor}/{$name}/');
    public function inflectPackageVars($vars)
    {
        return $this->parseVars($vars);
    }
    protected function parseVars($vars)
    {
        $vars['vendor'] = \strtolower($vars['vendor']) == 'sitedirect' ? 'SiteDirect' : $vars['vendor'];
        $vars['name'] = \str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = \str_replace(' ', '', \ucwords($vars['name']));
        return $vars;
    }
}
