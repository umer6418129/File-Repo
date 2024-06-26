<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite004f12bb121f868fb6df225511dc722
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lara\\Repo\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lara\\Repo\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInite004f12bb121f868fb6df225511dc722::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite004f12bb121f868fb6df225511dc722::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite004f12bb121f868fb6df225511dc722::$classMap;

        }, null, ClassLoader::class);
    }
}
