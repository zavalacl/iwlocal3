<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit03d975b71953f5b5f29642c9b0e90d69
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit03d975b71953f5b5f29642c9b0e90d69::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit03d975b71953f5b5f29642c9b0e90d69::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
