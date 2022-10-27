<?php

namespace Hiraeth\Routing;

use Hiraeth\Utils\Signal;

/**
 * {@inheritDoc}
 *
 * The signal adapter will match class (__invoke implied) and class::method type strings and wrap
 * them in a lazy loading signal resolver.
 */
class SignalAdapter implements Adapter
{
	/**
	 * The signal instance for resolving target to callables
	 *
	 * @var Signal|null
	 */
	protected $signal = NULL;


	/**
	 * Create a new instance of the adapter
	 */
	public function __construct(Signal $signal)
	{
		$this->signal = $signal;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return $this->signal->resolve($resolver->getTarget());
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		if (is_string($target = $resolver->getTarget())) {
			$callable = explode('::', $target) + [1 => '__invoke'];

			if (method_exists($callable[0], $callable[1])) {
				return TRUE;
			}
		}

		return FALSE;
	}
}
