<?php

$subsystems = glob(BASE_PATH . '/*/_heystack_subsystem', GLOB_NOSORT);
$extensions = array();

$placeExtension = function ($placeExtension, $extensions, $key, $extension) {
    if (isset($extensions[$key])) {
        $key++;
        $extensions = $placeExtension($placeExtension, $extensions, $key, $extension);
    } else {
        $extensions[$key] = $extension;
    }
    return $extensions;
};

if (is_array($subsystems)) {

    foreach ($subsystems as $dir) {

        $dir = dirname($dir);

        if (HEYSTACK_BASE_PATH != $dir) {

            $filename = $dir . '/config/extensions.php';

            if (file_exists($filename)) {

                $newExtensions = require_once $filename;

                foreach ($newExtensions as $key => $extension) {

                    $extensions = $placeExtension($placeExtension, $extensions, $key, $extension);

                }

            }

        }

    }

}

$extensions[0] = '\Heystack\Subsystem\Core\ContainerExtension';
$extensions[] = '\Heystack\Subsystem\Core\MysiteContainerExtension';

ksort($extensions);

return $extensions;
