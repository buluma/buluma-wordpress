<?php

namespace WPSentry\ScopedVendor\Composer\Installers;

use WPSentry\ScopedVendor\Composer\Composer;
use WPSentry\ScopedVendor\Composer\IO\IOInterface;
use WPSentry\ScopedVendor\Composer\Plugin\PluginInterface;
class Plugin implements \WPSentry\ScopedVendor\Composer\Plugin\PluginInterface
{
    private $installer;
    public function activate(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
        $this->installer = new \WPSentry\ScopedVendor\Composer\Installers\Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($this->installer);
    }
    public function deactivate(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }
    public function uninstall(\WPSentry\ScopedVendor\Composer\Composer $composer, \WPSentry\ScopedVendor\Composer\IO\IOInterface $io)
    {
    }
}
