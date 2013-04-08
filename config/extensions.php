<?php

use Camspiers\DependencyInjection\SharedContainerFactory;
use Heystack\Subsystem\Core\DependencyInjection;

SharedContainerFactory::addExtension(new DependencyInjection\ContainerExtension());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\EventDispatcher());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\InputProcessorHandler());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\OutputProcessorHandler());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\SilverStripeOrm());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\DataObjectGenerator());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\Command());
