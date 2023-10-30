<?php

namespace Hiraeth\Routing;

/**
 * {@inheritDoc}
 */
class NativeAdapter implements Adapter
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return $resolver->getRoute()->getTarget();
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		return is_callable($resolver->getRoute()->getTarget());
	}
}
