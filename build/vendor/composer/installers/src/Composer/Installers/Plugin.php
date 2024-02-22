<?php

namespace _PhpScoper9a3678ae6a12\Composer\Installers;

use _PhpScoper9a3678ae6a12\Composer\Composer;
use _PhpScoper9a3678ae6a12\Composer\IO\IOInterface;
use _PhpScoper9a3678ae6a12\Composer\Plugin\PluginInterface;
/** @internal */
class Plugin implements PluginInterface
{
    /** @var Installer */
    private $installer;
    public function activate(Composer $composer, IOInterface $io) : void
    {
        $this->installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($this->installer);
    }
    public function deactivate(Composer $composer, IOInterface $io) : void
    {
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }
    public function uninstall(Composer $composer, IOInterface $io) : void
    {
    }
}
