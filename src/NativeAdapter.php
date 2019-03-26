<?php

namespace Hiraeth\Routing;

/**
 *
 */
class NativeAdapter
{
	/**
	 *
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return $resolver->getTarget();
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		return is_callable($resolver->getTarget());
	}
}
