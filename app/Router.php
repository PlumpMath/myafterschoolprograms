<?php

/**
 * Routes domains to functions
 *
 * @author    Shashank Sanjay<ssanja1@pride.hofstra.edu>
 * @version   0.0.1 dev
 */


class Router
{
	protected $routes = [];

	public function __construct($routes)
	{
		$this->routes = $routes;
	}

	public function extractArguments($URL)
	{
		
	}

	public function 
}

$Router = new Router(['/' => 'View_Home',
					   '/user/::/' => 'View_User',
					   '/user/::/account/' => 'View_User_Account',
					   '/location/::/' => 'View_Location']);

echo $Router->callback('/user/1030/account/');
