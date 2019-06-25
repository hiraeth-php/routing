<?php

namespace Hiraeth\Routing;

use Hiraeth\Utils\Signal;

/**
 *
 */
class SignalAdapter implements AdapterInterface
{
	/**
	 *
	 */
	public function __construct(Signal $signal)
	{
		$this->signal = $signal;
	}


	/**
	 *
	 */
	public function __invoke(Resolver $resolver): callable
	{
		return $this->signal->resolve($resolver->getTarget());
	}


	/**
	 *
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
