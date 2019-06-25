<?php

namespace Hiraeth\Routing;


use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class ResponseAdapter implements AdapterInterface
{
	/**
	 *
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return function() use ($resolver) {
			return $resolver->getTarget();
		};
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		return $resolver->getTarget() instanceof Response;
	}
}
