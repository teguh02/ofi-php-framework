<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94043ae1bfc5ad1c0e568d555cd7b7b3
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/filp/whoops/src/Whoops',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit94043ae1bfc5ad1c0e568d555cd7b7b3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit94043ae1bfc5ad1c0e568d555cd7b7b3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
