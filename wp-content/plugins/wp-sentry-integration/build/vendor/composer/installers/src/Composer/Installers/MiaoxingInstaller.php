<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

class MiaoxingInstaller extends \WPSentry\ScopedVendor\Composer\Installers\BaseInstaller
{
    protected $locations = array('plugin' => 'plugins/{$name}/');
}
