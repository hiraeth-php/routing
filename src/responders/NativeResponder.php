<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * {@inheritDoc}
 */
class NativeResponder implements Responder
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): Response
	{
		return $resolver->getResult();
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		return $resolver->getResult() instanceof Response;
	}
}
