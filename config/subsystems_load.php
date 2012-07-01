<?php

$loader = require_once HEYSTACK_BASE_PATH . '/vendor/autoload.php';

use Symfony\Component\Config\FileLocator;

$locator = new FileLocator(glob(BASE_PATH . '/*', GLOB_ONLYDIR));

if ($subsystems = $locator->locate('_heystack_subsystem', null, false)) {

    foreach ($subsystems as $dir) {

        $dirname = dirname($dir);

        foreach (glob($dirname . '/code/Heystack/Subsystem/*', GLOB_ONLYDIR) as $subsystem) {

            $loader->add('Heystack\Subsystem\\' . basename($subsystem), $dirname . '/code');

        }

    }

    foreach ($subsystems as $dir) {

        $filename = dirname($dir) . '/config/services.php';

        if (file_exists($filename)) {

            require_once $filename;

        }

    }

}
