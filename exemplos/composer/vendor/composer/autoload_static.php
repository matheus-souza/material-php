<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit553e5b158316c9cbfa858f80c1ee79b0
{
    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit553e5b158316c9cbfa858f80c1ee79b0::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
