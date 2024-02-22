<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class SMFInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('module' => 'Sources/{$name}/', 'theme' => 'Themes/{$name}/');
}
