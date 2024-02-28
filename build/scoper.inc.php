<?php

declare (strict_types=1);
namespace _PhpScoper9a3678ae6a12;

use _PhpScoper9a3678ae6a12\Isolated\Symfony\Component\Finder\Finder;
// You can do your own things here, e.g. collecting symbols to expose dynamically
// or files to exclude.
// However beware that this file is executed by PHP-Scoper, hence if you are using
// the PHAR it will be loaded by the PHAR. So it is highly recommended to avoid
// to auto-load any code here: it can result in a conflict or even corrupt
// the PHP-Scoper analysis.
// Example of collecting files to include in the scoped build but to not scope
// leveraging the isolated finder.
$excludedFiles = \array_map(static fn(\SplFileInfo $fileInfo) => $fileInfo->getPathName(), \iterator_to_array(Finder::create()->files()->in(__DIR__), \false));
return [
    // The prefix configuration. If a non-null value is used, a random prefix
    // will be generated instead.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#prefix
    'prefix' => null,
    // The base output directory for the prefixed files.
    // This will be overridden by the 'output-dir' command line option if present.
    'output-dir' => null,
    // By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
    // directory. You can however define which files should be scoped by defining a collection of Finders in the
    // following configuration key.
    //
    // This configuration entry is completely ignored when using Box.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#finders-and-paths
    'finders' => [],
    // List of excluded files, i.e. files for which the content will be left untouched.
    // Paths are relative to the configuration file unless if they are already absolute
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'exclude-files' => [
        // 'src/an-excluded-file.php',
        ...$excludedFiles,
    ],
    // When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
    // original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
    // support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
    // heart contents.
    //
    // For more see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#patchers
    'patchers' => [static function (string $filePath, string $prefix, string $contents) : string {
        // Change the contents here.
        return $contents;
    }],
    // Whether symbols declarations should have the PHPDoc tag "@internal" added. This helps some IDEs to not indicate
    // to not include the symbol for auto-completion suggestions for example.
    'tag-declarations-as-internal' => \true,
    // List of symbols to consider internal i.e. to leave untouched.
    //
    // For more information see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#excluded-symbols
    'exclude-namespaces' => [],
    'exclude-classes' => [],
    'exclude-functions' => [],
    'exclude-constants' => [],
    // List of symbols to expose.
    //
    // For more information see: https://github.com/humbug/php-scoper/blob/master/docs/configuration.md#exposed-symbols
    'expose-global-constants' => \true,
    'expose-global-classes' => \true,
    'expose-global-functions' => \true,
    'expose-namespaces' => [],
    'expose-classes' => [],
    'expose-functions' => [],
    'expose-constants' => [],
];