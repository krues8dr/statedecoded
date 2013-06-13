<?php 

/**
 * MasterController class
 *
 * Routes all requests to the proper controllers.
 * 
 * PHP version 5
 *
 * @author		Bill Hunt <bill at krues8dr dot com>
 * @copyright	2013 Bill Hunt
 * @license		http://www.gnu.org/licenses/gpl.html GPL 3
 * @version		0.8
 * @link		http://www.statedecoded.com/
 * @since		0.8
 */

class MasterController
{
	public $routes = array();
	
	public function __construct()
	{
		
	}
	
	public function execute()
	{
		/* 
		 * Explode the request into a method and some args
		 */ 
		list($class, $method, $args) = $this->parseRequest();
		
		$object = new $class();
		print $object->$method($args);
	}
	
	public function parseRequest()
	{
		// Reformat the results slightly.
		list($handler, $args) = Router::getRoute($_SERVER['REQUEST_URI']);
		return array($handler[0], $handler[1], $args);
	}
	
	public function fetchRoutes()
	{
		
	}
	
}
