<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;

/**
 *
 */
class NativeResponder implements Responder
{
	/**
	 *
	 */
	public function __invoke(Resolver $resolver): Response
	{
		return $resolver->getResult();
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		return $resolver->getResult() instanceof Response;
	}
}
