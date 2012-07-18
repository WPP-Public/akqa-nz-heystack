<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * 
 */
use Heystack\Subsystem\Core\ServiceStore;

/**
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Cameron Spiers <cam@heyday.co.nz>
 * @package Heystack
 *
 */
class CliInputController extends CliController
{

    public static $url_segment = 'heystack/cli/input';

    private $stateService;
    private $inputHandlerService;
    private $outputHandlerService;

    /**
     * Setup this controller.
     */
    public function __construct()
    {

        parent::__construct();

        $this->inputHandlerService = ServiceStore::getService('cli_input_processor_handler');

    }

    /**
     * Process the request to the controller and direct it to the correct input
     * and output controllers via the input and output processor services.
     *
     * @return mixed
     */
    public function process()
    {

        return $this->inputHandlerService->process($request->param('Processor'), $this->getRequest());

    }

}
