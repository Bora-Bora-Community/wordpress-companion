<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class EliasisInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('component' => 'components/{$name}/', 'module' => 'modules/{$name}/', 'plugin' => 'plugins/{$name}/', 'template' => 'templates/{$name}/');
}
