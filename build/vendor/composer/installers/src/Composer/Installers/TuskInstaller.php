<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/**
 * Composer installer for 3rd party Tusk utilities
 * @author Drew Ewing <drew@phenocode.com>
 * @internal
 */
class TuskInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('task' => '.tusk/tasks/{$name}/', 'command' => '.tusk/commands/{$name}/', 'asset' => 'assets/tusk/{$name}/');
}
