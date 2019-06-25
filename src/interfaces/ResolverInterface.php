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
 interface ResolverInterface
 {
	/**
	 * Resolve a target returned by `RouterInterface::match()` to a PSR-7 response
	 *
	 * @access public
	 * @param Route $route The route to run
	 * @param Request $request The server request that matched the target
	 * @param Response $response The response object to modify for return
	 * @return Response The PSR-7 response from running the target
	 */
	 public function run(Route $route, Request $request, Response $response): Response;


	/**
	 *
	 * @access public
	 * @return Request The PSR-7 request used to run the resolver
	 */
	 public function getRequest(): Request;


	/**
	 * @access public
	 * @return Response The PSR-7 response used to run the resolver
	 */
	 public function getResponse(): Response;
 }
