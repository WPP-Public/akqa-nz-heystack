<?php

use Camspiers\DependencyInjection\SharedContainerFactory;

SharedContainerFactory::addExtension(new Heystack\Subsystem\Core\ContainerExtension);
SharedContainerFactory::addExtension(new Heystack\Subsystem\Core\MysiteContainerExtension);

SharedContainerFactory::addCompilerPass(new MergeExtensionCallsConfigurationPass());
SharedContainerFactory::addCompilerPass(new MergeExtensionArgumentsConfigurationPass());
