<?php

use Heystack\Core\DependencyInjection\CompilerPass;

return [
    new CompilerPass\EventDispatcher(),
    new CompilerPass\InputProcessorHandler(),
    new CompilerPass\OutputProcessorHandler(),
    new CompilerPass\SilverStripeOrm(),
    new CompilerPass\DataObjectGenerator(),
    new CompilerPass\Command(),
    new CompilerPass\HasHeystackServices(),
];