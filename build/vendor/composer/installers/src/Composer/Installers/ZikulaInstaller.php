<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class ZikulaInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'modules/{$vendor}-{$name}/', 'theme' => 'themes/{$vendor}-{$name}/');
}
