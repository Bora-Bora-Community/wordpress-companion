<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class PhiftyInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('bundle' => 'bundles/{$name}/', 'library' => 'libraries/{$name}/', 'framework' => 'frameworks/{$name}/');
}
