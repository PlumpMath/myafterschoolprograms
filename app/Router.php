<?php

/**
 * Routes domains to functions
 *
 * @author    Shashank Sanjay<ssanja1@pride.hofstra.edu>
 * @version   0.0.1 dev
 */


class Router
{
	//Defines routes URL -> Function
	protected $routes = [
		"foo/bar" => "fooBar",
	];

	// This way, {$routes[$key]}() will execute the associated function
	
	/**/
	public function fooBar()
	{
	    return "Foo Bar";
	}

	public function getRoute($key)
	{
		$callback = $this->routes[$key];

		return $this->$callback();
	}
	/**/
}

$Router = new Router;

echo $Router->getRoute("foo/bar");

	/** /
	function RouteURL() 
	{
		//Accept URL
		$incomingURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$path = explode('/', $incomingURL);
		foreach ($route in key($routes)) {
			if ($route == $path) {
				//execute current($routes);
				$functionURL = current($routes);
			}; //understand this may not work as path is not in same form as route
			
			//if URL does not map to a function
			if is_null($functionURL) {
					$functionURL = "This page does not exist";
				}
		
			//Output
			echo $functionURL;
		}
	}
	/**/
