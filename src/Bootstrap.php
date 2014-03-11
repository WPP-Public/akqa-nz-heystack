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
     * @var DependencyInjection\SilverStripe\HeystackSilverStripeContainer
     */
    protected $container;

    /**
     * @param \Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer $container
     */
    public function __construct(HeystackSilverStripeContainer $container)
    {
        $this->container = $container;
    }
    
    /**
     * @param SS_HTTPRequest $request
     * @param Session $session
     * @param DataModel $model
     * @return bool|void
     */
    public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model)
    {
        $this->doBootstrap($session);
        $this->eventDispatcher->dispatch(Events::PRE_REQUEST);
    }

    /**
     * Filter executed AFTER a request
     */
    public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model)
    {
        $this->eventDispatcher->dispatch(Events::POST_REQUEST);
    }

    /**
     * @param Session $session
     */
    public function doBootstrap(Session $session)
    {
        \Injector::inst()->setObjectCreator(new HeystackInjectionCreator($this->container));
        $this->container->get(Services::BACKEND_SESSION)->setSession($session);
        $this->eventDispatcher = $this->container->get(Services::EVENT_DISPATCHER);
    }
}