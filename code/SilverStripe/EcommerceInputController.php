<?php

class EcommerceInputController extends Controller
{
    
    public static $url_segment = 'ecommerce/input';
    
    private $stateService;
    private $handlerService;
    
    public function __construct()
    {
        
        parent::__construct();
        
        $this->stateService = \Heystack\Subsystem\Core\ServiceStore::getService('state');
        $this->handlerService = \Heystack\Subsystem\Core\ServiceStore::getService('processor_handler');
        
    }
    
    public function process()
    {
        
        $request = $this->getRequest();
        
        if ($this->handlerService->hasProcessor($request->param('ID'))) {
            
            $this->handlerService->getProcessor($request->param('ID'))->process($request);
            
        }
        
    }
    
    
}