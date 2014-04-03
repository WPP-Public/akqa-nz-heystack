<?php

namespace Heystack\Core;

use DataModel;
use Session;
use SS_HTTPRequest;
use SS_HTTPResponse;
use Heystack\Core\DependencyInjection\SilverStripe\HeystackInjectionCreator;
use Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer;

/**
 * Sets up heystack by bootstrap the bridge between heystack and SilverStripe
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
     * @var bool
     */
    protected $registered = false;

    /**
     * @param \Heystack\Core\DependencyInjection\SilverStripe\HeystackSilverStripeContainer $container
     */
    public function __construct(HeystackSilverStripeContainer $container = null)
    {
        if (!is_null($container)) {
            $this->container = $container;
        }
    }
    
    /**
     * @param SS_HTTPRequest $request
     * @param Session $session
     * @param DataModel $model
     * @return void
     */
    public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model)
    {
        if (!$this->registered) {
            $this->doBootstrap($session);
            $this->registered = true;
        }
        $this->eventDispatcher->dispatch(Events::PRE_REQUEST);
    }

    /**
     * @param SS_HTTPRequest $request
     * @param SS_HTTPResponse $response
     * @param DataModel $model
     * @return void
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
        if ($this->container === null) {
            require_once HEYSTACK_BASE_PATH . '/config/container.php';
            $containerClassName = 'HeystackServiceContainer' . \Director::get_environment_type();
            $this->container = new $containerClassName;
        }
        \Injector::inst()->setObjectCreator(new HeystackInjectionCreator($this->container));
        $this->container->get(Services::BACKEND_SESSION)->setSession($session);
        $this->eventDispatcher = $this->container->get(Services::EVENT_DISPATCHER);
    }
}