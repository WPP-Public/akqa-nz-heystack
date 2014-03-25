<?php

use Heyday\AutoInject\AutoInject;
use Heystack\Core\DependencyInjection\CompilerPass;

return [
    new CompilerPass\EventDispatcher(),
    new CompilerPass\InputProcessorHandler(),
    new CompilerPass\OutputProcessorHandler(),
    new CompilerPass\SilverStripeOrm(),
    new CompilerPass\SchemaService(),
    new CompilerPass\Command(),
    new CompilerPass\HasLoggerService(),
    new CompilerPass\State(),
    new AutoInject()
];
