<?php

use Camspiers\DependencyInjection\SharedContainerFactory;

$file = HEYSTACK_BASE_PATH . '/cache/HeystackServiceContainer.php';

if (file_exists($file)) {

    require_once $file;
    $container = new HeystackServiceContainer();

} else {
    echo 'Heystack requires a container to run. Please run \'heystack generate-container\'', PHP_EOL;
    exit(1);
}

Heystack\Subsystem\Core\ServiceStore::set($container);

return $container;
