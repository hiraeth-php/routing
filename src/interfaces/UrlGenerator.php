<?php

namespace Hiraeth\Routing;

/**
 * URL Generators combine a location an parameters into a URL string
 *
 * How the parameters are integrated or applied to the location is up to the specific
 * implementation.  For example, it can parse the location to replace some parameters in the
 * path and append the rest as a query string, it can filter parameters if value is NULL, etc.
 *
 * Additionally, the supported locations are allowed to vary (not a strict interface).  Different
 * genreators may support strings, request objects, etc.
 */
interface UrlGenerator
{
	/**
	 * Convert the provided location and parameters to a string URL
	 */
	public function __invoke($location, array $params = array()): string;
}
