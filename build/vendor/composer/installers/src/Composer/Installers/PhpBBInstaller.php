<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class PhpBBInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('extension' => 'ext/{$vendor}/{$name}/', 'language' => 'language/{$name}/', 'style' => 'styles/{$name}/');
}
