<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
interface ResponderInterface
{
	/**
	 *
	 */
	public function __invoke(Resolver $resolver): Response;


	/**
	 *
	 */
	public function match(Resolver $resolver): bool;
}
