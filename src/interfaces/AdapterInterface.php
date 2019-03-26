<?php

namespace Hiraeth\Routing;


/**
 *
 */
interface AdapterInterface
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
