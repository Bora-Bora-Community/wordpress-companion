<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class ZendInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('library' => 'library/{$name}/', 'extra' => 'extras/library/{$name}/', 'module' => 'module/{$name}/');
}