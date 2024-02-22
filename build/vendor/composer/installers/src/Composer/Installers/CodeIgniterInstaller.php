<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class CodeIgniterInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('library' => 'application/libraries/{$name}/', 'third-party' => 'application/third_party/{$name}/', 'module' => 'application/modules/{$name}/');
}
