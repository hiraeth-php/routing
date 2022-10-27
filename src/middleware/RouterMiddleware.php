<?php

namespace Hiraeth\Routing;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 *
 */
class RouterMiddleware implements MiddlewareInterface
{
	/**
	 * @var ResponseFactory|null
	 */
	protected $factory = NULL;


	/**
	 * @var Resolver|null
	 */
	protected $resolver = NULL;


	/**
	 * @var Router|null
	 */
	protected $router = NULL;


	/**
	 *
	 */
	public function __construct(Resolver $resolver, ResponseFactory $factory, Router $router)
	{
		$this->resolver = $resolver;
		$this->factory  = $factory;
		$this->router   = $router;
	}


	/**
	 *
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		$response = $this->factory->createResponse(200);
		$route    = $this->router->match($request, $response);

		return $this->resolver->run($route, $request, $response);
	}
}
