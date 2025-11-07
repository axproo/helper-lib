<?php

namespace Axproo\HelperLib;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Installer
{
    /**
     * Installation automatique du répertoire de langues
     *
     * @param Event|PackageEvent|null $event
     */
    public static function install($event = null)
    {
        // Gestion de l'interface IO
        $io = null;
        if ($event instanceof Event || $event instanceof PackageEvent) {
            $io = $event->getIO();
        }

        if ($io) {
            $io->write("<info>Installing Axproo Helper Library...</info>");
        } else {
            echo "Installing Axproo Helper Library...\n";
        }

        // Détection des chemins
        $rootDir = getcwd();
        $vendorDir = $rootDir . '/vendor/axproo/helper-lib/src/Helpers';
        $destDir = $rootDir . '/app/Helpers';

        if (!is_dir($vendorDir)) {
            if ($io) {
                $io->write("<error>Source folder not found: {$vendorDir}</error>");
            } else {
                echo "Source folder not found: {$vendorDir}\n";
            }
            return;
        }

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        self::copyRecursive($vendorDir, $destDir);

        if ($io) {
            $io->write("<info>Helpers successfully installed in app/Helpers.</info>");
        } else {
            echo "Helpers successfully installed in app/Helpers.\n";
        }
    }

    private static function copyRecursive($src, $dest)
    {
        $dir = opendir($src);
        @mkdir($dest, 0755, true);

        while (($file = readdir($dir)) !== false) {
            if ($file == '.' || $file == '..') continue;

            $srcPath = "$src/$file";
            $destPath = "$dest/$file";

            if (is_dir($srcPath)) {
                self::copyRecursive($srcPath, $destPath);
            } else {
                copy($srcPath, $destPath);
            }
        }

        closedir($dir);
    }
}
