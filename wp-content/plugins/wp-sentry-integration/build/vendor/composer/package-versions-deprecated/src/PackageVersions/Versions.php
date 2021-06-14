<?php

declare (strict_types=1);
namespace WPSentry\ScopedVendor\PackageVersions;

use WPSentry\ScopedVendor\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class);
/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'stayallive/wp-sentry';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS = array('clue/stream-filter' => 'v1.5.0@aeb7d8ea49c7963d3b581378955dbf5bc49aa320', 'composer/installers' => 'v1.11.0@ae03311f45dfe194412081526be2e003960df74b', 'composer/package-versions-deprecated' => '1.11.99.2@c6522afe5540d5fc46675043d3ed5a45a740b27c', 'guzzlehttp/promises' => '1.4.1@8e7d04f1f6450fef59366c399cfad4b9383aa30d', 'guzzlehttp/psr7' => '1.8.2@dc960a912984efb74d0a90222870c72c87f10c91', 'http-interop/http-factory-guzzle' => '1.0.0@34861658efb9899a6618cef03de46e2a52c80fc0', 'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303', 'php-http/client-common' => '2.3.0@e37e46c610c87519753135fb893111798c69076a', 'php-http/curl-client' => '2.2.0@15b11b7c2f39fe61ef6a70e0c247b4a84e845cdb', 'php-http/discovery' => '1.14.0@778f722e29250c1fac0bbdef2c122fa5d038c9eb', 'php-http/httplug' => '2.2.0@191a0a1b41ed026b717421931f8d3bd2514ffbf9', 'php-http/message' => '1.11.1@887734d9c515ad9a564f6581a682fff87a6253cc', 'php-http/message-factory' => 'v1.0.2@a478cb11f66a6ac48d8954216cfed9aa06a501a1', 'php-http/promise' => '1.1.0@4c4c1f9b7289a2ec57cde7f1e9762a5789506f88', 'psr/http-client' => '1.0.1@2dfb5f6c5eff0e91e20e913f8c5452ed95b86621', 'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be', 'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363', 'psr/log' => '1.1.4@d49695b909c3b7628b6289db5479a1c204601f11', 'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822', 'sentry/sentry' => '3.3.0@3bb122f9fc2bc43a4646e37db79eaf115b35b047', 'symfony/options-resolver' => 'v4.4.25@2e607d627c70aa56284a02d34fc60dfe3a9a352e', 'symfony/polyfill-php80' => 'v1.23.0@eca0bf41ed421bed1b57c4958bab16aa86b757d0', 'symfony/polyfill-uuid' => 'v1.23.0@9165effa2eb8a31bb3fa608df9d529920d21ddd9', 'stayallive/wp-sentry' => 'v4.4.0@b6085e830ce2d5fa32fbb9c90adceef04c448d87');
    private function __construct()
    {
    }
    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!\class_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, \false) || !(\method_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, 'getAllRawData') ? \WPSentry\ScopedVendor\Composer\InstalledVersions::getAllRawData() : \WPSentry\ScopedVendor\Composer\InstalledVersions::getRawData())) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \WPSentry\ScopedVendor\Composer\InstalledVersions::getRootPackage()['name'];
    }
    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName) : string
    {
        if (\class_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, \false) && (\method_exists(\WPSentry\ScopedVendor\Composer\InstalledVersions::class, 'getAllRawData') ? \WPSentry\ScopedVendor\Composer\InstalledVersions::getAllRawData() : \WPSentry\ScopedVendor\Composer\InstalledVersions::getRawData())) {
            return \WPSentry\ScopedVendor\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \WPSentry\ScopedVendor\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
