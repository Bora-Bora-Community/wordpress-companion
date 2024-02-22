<?php

namespace _PhpScoper9a3678ae6a12;

use _PhpScoper9a3678ae6a12\Composer\Autoload\ClassLoader;
/** @internal */
function includeIfExists(string $file) : ?ClassLoader
{
    if (\file_exists($file)) {
        return include $file;
    }
    return null;
}
if (!($loader = includeIfExists(__DIR__ . '/../vendor/autoload.php')) && !($loader = includeIfExists(__DIR__ . '/../../../autoload.php'))) {
    die('You must set up the project dependencies, run the following commands:' . \PHP_EOL . 'curl -s http://getcomposer.org/installer | php' . \PHP_EOL . 'php composer.phar install' . \PHP_EOL);
}
return $loader;
