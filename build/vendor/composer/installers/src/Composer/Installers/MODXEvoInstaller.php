<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/**
 * An installer to handle MODX Evolution specifics when installing packages.
 * @internal
 */
class MODXEvoInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('snippet' => 'assets/snippets/{$name}/', 'plugin' => 'assets/plugins/{$name}/', 'module' => 'assets/modules/{$name}/', 'template' => 'assets/templates/{$name}/', 'lib' => 'assets/lib/{$name}/');
}
