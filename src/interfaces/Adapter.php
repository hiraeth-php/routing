<?php

namespace Hiraeth\Routing;

/**
 *
 */
interface Adapter
{
	/**
	 *
	 */
	public function __invoke(Resolver $resolver): callable;


	/**
	 *
	 */
	public function match(Resolver $resolver): bool;
}
