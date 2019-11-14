<?php

namespace Hiraeth\Routing;

/**
 *
 */
interface UrlGenerator
{
	/**
	 *
	 */
	public function __invoke($location, array $params = array()): string;
}
