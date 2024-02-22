<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

/** @internal */
class KodiCMSInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    protected $locations = array('plugin' => 'cms/plugins/{$name}/', 'media' => 'cms/media/vendor/{$name}/');
}
