<?php

if (file_exists(HEYSTACK_BASE_PATH . '/vendor/autoload.php')) {

    $loader = require_once HEYSTACK_BASE_PATH . '/vendor/autoload.php';

    $subsystems = glob(BASE_PATH . '/*/_heystack_subsystem', GLOB_NOSORT);

    if (is_array($subsystems)) {

        foreach ($subsystems as $dir) {

            $dir = dirname($dir);

            if (HEYSTACK_BASE_PATH != $dir) {

                $composerDir = $dir . '/vendor/composer';

                $map = require $composerDir . '/autoload_namespaces.php';
                foreach ($map as $namespace => $path) {
                    $loader->add($namespace, $path);
                }

                $classMap = require $composerDir . '/autoload_classmap.php';
                if ($classMap) {
                    $loader->addClassMap($classMap);
                }

            }

        }

    }
    
}