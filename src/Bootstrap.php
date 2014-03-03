<?php

namespace Heystack\Core;

use DataModel;
use Session;
use SS_HTTPRequest;
use SS_HTTPResponse;
use Heystack\Core\DependencyInjection\SilverStripe\HeystackInjectionCreator;
use Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer;

/**
 * Class RequestFilter
 * @package Heystack\Core
 */
class Bootstrap implements \RequestFilter
{
    /**
     * @var \Heystack\Core\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param \Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer $container
     */
    public function __construct(HeystackSilverStripeContainer $container)
    {
        \Injector::inst()->setObjectCreator(new HeystackInjectionCreator($container));
        $this->eventDispatcher = $container->get(Services::EVENT_DISPATCHER);
    }
    
    /**
     * @param SS_HTTPRequest $request
     * @param Session $session
     * @param DataModel $model
     * @return bool|void
     */
    public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model)
    {
        $this->eventDispatcher->dispatch(Events::PRE_REQUEST);
    }

    /**
     * Filter executed AFTER a request
     */
    public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model)
    {
        $this->eventDispatcher->dispatch(Events::POST_REQUEST);
    }
}