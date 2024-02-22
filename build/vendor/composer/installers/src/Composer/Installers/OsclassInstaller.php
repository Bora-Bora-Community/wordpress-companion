<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class OsclassInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('plugin' => 'oc-content/plugins/{$name}/', 'theme' => 'oc-content/themes/{$name}/', 'language' => 'oc-content/languages/{$name}/');
}
