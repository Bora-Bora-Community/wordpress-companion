<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class PrestashopInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'modules/{$name}/', 'theme' => 'themes/{$name}/');
}
