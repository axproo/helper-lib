<?php

namespace Axproo\HelperLib;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io) {}
    public function deactivate(Composer $composer, IOInterface $io) {}
    public function uninstall(Composer $composer, IOInterface $io) {}

    public static function getSubscribedEvents()
    {
        return [
            'post-package-install' => 'onPostPackageEvent',
            'post-package-update'  => 'onPostPackageEvent',
        ];
    }

    public function onPostPackageEvent(PackageEvent $event)
    {
        $operation = $event->getOperation();
        $package = null;

        // ✅ Vérifie le type d'opération pour récupérer le bon objet package
        if ($operation instanceof InstallOperation) {
            $package = $operation->getPackage();
        } elseif ($operation instanceof UpdateOperation) {
            $package = $operation->getTargetPackage();
        }

        // Si on ne trouve pas le package, on sort
        if (!$package) {
            return;
        }

        // ✅ Vérifie si c’est bien notre librairie
        if ($package->getName() === 'axproo/helper-lib') {
            Installer::install($event);
        }
    }
}
