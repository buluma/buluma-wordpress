<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805
{
    public static $files = array (
        'e7a23b473708f4b0fb5b99fe921bee83' => __DIR__ . '/../..' . '/public/includes/lib/widgets-helper/wph-widget.php',
        '995b589e693a50f54393aa31ee1c3763' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/facades/wordpress.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/admin/includes/lib/wp-admin-notice',
    );

    public static $prefixesPsr0 = array (
        'x' => 
        array (
            'xrstf\\Composer52' => 
            array (
                0 => __DIR__ . '/..' . '/xrstf/composer-php52/lib',
            ),
        ),
        'C' => 
        array (
            'Composer\\CustomDirectoryInstaller' => 
            array (
                0 => __DIR__ . '/..' . '/mnsami/composer-custom-directory-installer/src',
            ),
        ),
    );

    public static $classMap = array (
        'Whip_BasicMessage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/messages/Whip_BasicMessage.php',
        'Whip_Configuration' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_Configuration.php',
        'Whip_DismissStorage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_DismissStorage.php',
        'Whip_EmptyProperty' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/exceptions/Whip_EmptyProperty.php',
        'Whip_Host' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_Host.php',
        'Whip_HostMessage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/messages/Whip_HostMessage.php',
        'Whip_InvalidOperatorType' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/exceptions/Whip_InvalidOperatorType.php',
        'Whip_InvalidType' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/exceptions/Whip_InvalidType.php',
        'Whip_InvalidVersionComparisonString' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/exceptions/Whip_InvalidVersionComparisonString.php',
        'Whip_InvalidVersionRequirementMessage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/messages/Whip_InvalidVersionRequirementMessage.php',
        'Whip_Listener' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_Listener.php',
        'Whip_Message' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_Message.php',
        'Whip_MessageDismisser' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_MessageDismisser.php',
        'Whip_MessageFormatter' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_MessageFormatter.php',
        'Whip_MessagePresenter' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_MessagePresenter.php',
        'Whip_MessagesManager' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_MessagesManager.php',
        'Whip_NullMessage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/messages/Whip_NullMessage.php',
        'Whip_Requirement' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_Requirement.php',
        'Whip_RequirementsChecker' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_RequirementsChecker.php',
        'Whip_UpgradePhpMessage' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/messages/Whip_UpgradePhpMessage.php',
        'Whip_VersionDetector' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/interfaces/Whip_VersionDetector.php',
        'Whip_VersionRequirement' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_VersionRequirement.php',
        'Whip_WPDismissOption' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_WPDismissOption.php',
        'Whip_WPMessageDismissListener' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/Whip_WPMessageDismissListener.php',
        'Whip_WPMessagePresenter' => __DIR__ . '/../..' . '/admin/includes/lib/whip/src/presenters/Whip_WPMessagePresenter.php',
        'Yoast_I18n_WordPressOrg_v3' => __DIR__ . '/../..' . '/admin/includes/lib/i18n-module/src/i18n-module-wordpressorg.php',
        'Yoast_I18n_v3' => __DIR__ . '/../..' . '/admin/includes/lib/i18n-module/src/i18n-module.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit2ba1da21402df1f56ccc45ad5db0c805::$classMap;

        }, null, ClassLoader::class);
    }
}
