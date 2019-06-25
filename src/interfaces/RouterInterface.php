<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * The router interface is designed to give a common interface to routers for integration
 * into hiraeth.
 *
 * Generally this interface will be implemented by a proxy class which will wrap calls to a
 * specific router and normalize the behavior.
 */
 interface RouterInterface
 {
	 /**
	  * Match an incoming request and return a result.
	  *
	  * If the route cannot be matched, this result target should contain the modified response
	  * with an appropriate status and information e.g. 404, 403, etc.
	  *
	  * @access public
	  * @param Request $request The server request to try and match against a route
	  * @param Response $response The default response to be modified in the event of errors
	  * @return Route The route to run
	  */
	 public function match(Request $request, Response $response): Route;
 }
