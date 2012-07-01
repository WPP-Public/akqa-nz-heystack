<?php

$loader = require_once HEYSTACK_BASE_PATH . '/vendor/autoload.php';

$subsystems = glob(BASE_PATH . '/*/_heystack_subsystem', GLOB_NOSORT);

if (is_array($subsystems)) {

    foreach ($subsystems as $dir) {

        $dir = dirname($dir);

        foreach (glob($dir . '/code/Heystack/Subsystem/*', GLOB_ONLYDIR | GLOB_NOSORT) as $subsystem) {

            $loader->add('Heystack\Subsystem\\' . basename($subsystem), $dir . '/code');

        }

        $filename = $dir . '/config/services.php';

        if (file_exists($filename)) {

            require_once $filename;

        }

    }

}
