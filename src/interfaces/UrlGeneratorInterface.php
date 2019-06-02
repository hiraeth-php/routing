<?php

namespace Hiraeth\Routing;

/**
 *
 */
interface UrlGeneratorInterface
{
	/**
	 *
	 */
	public function anchor($location, array $params = array()): string;
}
