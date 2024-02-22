<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class VanillaInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('plugin' => 'plugins/{$name}/', 'theme' => 'themes/{$name}/');
}
