<?php

namespace Hiraeth\Routing;


use Psr\Http\Message\ResponseInterface as Response;

/**
 * {@inheritDoc}
 */
class ResponseAdapter implements Adapter
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return fn() => $resolver->getRoute()->getTarget();
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		return $resolver->getRoute()->getTarget() instanceof Response;
	}
}
