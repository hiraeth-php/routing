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
	public function anchor($location, array $params = array()): string;
}
