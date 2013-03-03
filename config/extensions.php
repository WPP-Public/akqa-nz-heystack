<?php

use Camspiers\DependencyInjection\SharedContainerFactory;

SharedContainerFactory::addExtension(new Heystack\Subsystem\Core\DependencyInjection\ContainerExtension);

SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Core\DependencyInjection\CompilerPass\EventDispatcher);
SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Core\DependencyInjection\CompilerPass\InputProcessorHandler);
SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Core\DependencyInjection\CompilerPass\OutputProcessorHandler);
SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Core\DependencyInjection\CompilerPass\SilverStripeOrm);
SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Core\DependencyInjection\CompilerPass\DataObjectGenerator);
