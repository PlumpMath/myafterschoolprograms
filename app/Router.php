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
	protected $routes = array(
		example/URL => examplefunction(),
		example/user/id => exampleuserfunction()
		
	public $functionURL;
	
	);
	
	function RouteURL() {
		//Accept URL
		$incomingURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
		/**
		* Parse URL to match
		* Explode isn't right, it outputs the URL into path as elements
		* I want it to be the string we are evaluating, so /member/:id
		*/
		$path = explode('/', $incomingURL);
	
		/**
		* Match URL to function by checking
		* the key of each element in $routes array
		*/
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
	};

}

?>