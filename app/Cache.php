<?php

// {{{ Cache

/**
 * Retrieves from and builds cache
 *
 * @author    William Stein <steinbilly@gmail.com>
 * @version   0.0.1-dev
 */

class Cache
{
    // {{{ properties
    
    public $path;
	public $expiration = 3600;

    // }}}
    // {{{ __construct
  
    /**
     * constructor
	 *
	 * @param    $path    path to cache, default is /
     */

    function __construct($path = '/') 
    {
	    $this->path = $path;
    }

    // }}}
	// {{{ query

	/**
	 * Checks if file is cached
	 *
	 * @todo                  implement change based caching
	 * @param    $filename    checks for $filename in cache
	 * @return   $result      file if $filename exists, or NULL
	 */

	function query($filename)
	{
	    $filename = $this->path . "//" . $filename;
	    $result = NULL;

	    if (file_exists($filename)) {
		    if (time() - filemtime($filename) < $this->expiration) {
			    $result = file_get_contents($filename);
		    }
	    }

	    return $result;
	}

	// }}}
    // {{{ put

	/**
	 * Puts file in cache
	 *
	 * @param    $filename    name of file to cache
	 * @param    $content     content of file to cache
	 * @return   $cached      true if successful, error if not
	 */

	function put($filename, $content)
	{
	    return -1;
	}

	// }}}
}

// }}}
