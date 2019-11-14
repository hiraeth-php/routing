<?php

namespace Hiraeth\Routing;

/**
 *
 */
class NativeAdapter implements Adapter
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
