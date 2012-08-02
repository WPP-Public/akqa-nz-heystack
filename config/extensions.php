<?php

$subsystems = glob(BASE_PATH . '/*/_heystack_subsystem', GLOB_NOSORT);
$extensions = array();

if (is_array($subsystems)) {

    foreach ($subsystems as $dir) {

        $dir = dirname($dir);

        if (HEYSTACK_BASE_PATH != $dir) {

            $filename = $dir . '/config/extensions.php';

            if (file_exists($filename)) {

                $extensions = $extensions + require_once $filename;

            }

        }

    }

}

$extensions[0] = '\Heystack\Subsystem\Core\ContainerExtension';
$extensions[] = '\Heystack\Subsystem\Core\MysiteContainerExtension';

ksort($extensions);

return $extensions;
