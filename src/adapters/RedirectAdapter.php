<?php

namespace Hiraeth\Routing;

/**
 * {@inheritDoc}
 */
class RedirectAdapter implements Adapter
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return function() use ($resolver) {
			if ($resolver->getRequest()->getMethod() == 'GET') {
				$status = 301;
			} else {
				$status = 308;
			}

			return $resolver->getResponse()->withStatus($status)->withHeader(
				'Location', substr($resolver->getRoute()->getTarget(), 1)
			);
		};
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		if (is_string($target = $resolver->getRoute()->getTarget())) {
			return strpos($target, '=') === 0;
		}

		return FALSE;
	}
}
