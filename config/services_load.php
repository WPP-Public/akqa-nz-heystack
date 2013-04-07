<?php

use Camspiers\DependencyInjection\SharedContainerFactory;

$file = HEYSTACK_BASE_PATH . '/cache/HeystackServiceContainer.php';

if (file_exists($file) && !isset($_GET['flush'])) {

    require_once $file;
    $container = new HeystackServiceContainer();

} else {
	
	$servicesConfigruationPath = HEYSTACK_BASE_PATH . '/config';
	
	if(file_exists(BASE_PATH . '/mysite/config/services.yml')){
		
		$servicesConfigruationPath = BASE_PATH . '/mysite/config';
		
	}
	
	SharedContainerFactory::requireExtensionConfigs(
        array(
            BASE_PATH . '/*/config/extensions.php'
        )
    );
	
	SharedContainerFactory::dumpContainer(
		$container = SharedContainerFactory::createContainer(
			array(),
			$servicesConfigruationPath . '/services.yml'
		),
		'HeystackServiceContainer',
		HEYSTACK_BASE_PATH . '/cache/'
	);

}

Heystack\Subsystem\Core\ServiceStore::set($container);

return $container;
