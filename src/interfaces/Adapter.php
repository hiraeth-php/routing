<?php

namespace Hiraeth\Routing;

/**
 * Adapters convert a resolved routing target to a callable
 */
interface Adapter
{
	/**
	 * Convert the resolver target to a callable
	 */
	public function __invoke(Resolver $resolver): callable;


	/**
	 * Match whether or not this adapter should be used to convert the target to a callable
	 */
	public function match(Resolver $resolver): bool;
}
