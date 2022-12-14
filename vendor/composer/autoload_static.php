<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5a223d4e2590231cafc6f2b251b078dc
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'G28\\B2bkingext\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'G28\\B2bkingext\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5a223d4e2590231cafc6f2b251b078dc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5a223d4e2590231cafc6f2b251b078dc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5a223d4e2590231cafc6f2b251b078dc::$classMap;

        }, null, ClassLoader::class);
    }
}
