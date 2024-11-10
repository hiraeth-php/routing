<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 *
 */
interface Resolvable
{
	/**
	 * Set the resolver (and implicitely the default request/response)
	 */
	public function setResolver(Resolver $resolver): self;
}
