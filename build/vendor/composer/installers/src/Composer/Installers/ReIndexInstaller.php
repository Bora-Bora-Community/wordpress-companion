<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class ReIndexInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('theme' => 'themes/{$name}/', 'plugin' => 'plugins/{$name}/');
}
