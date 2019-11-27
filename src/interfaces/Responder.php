<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Responders convert the result of a routed action into a PSR-7 response
 */
interface Responder
{
	/**
	 * Convert the resolver result to a response
	 */
	public function __invoke(Resolver $resolver): Response;


	/**
	 * Match whether or not this responder should be used to convert the result to a callable
	 */
	public function match(Resolver $resolver): bool;
}
