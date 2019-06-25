<?php

namespace Hiraeth\Routing;

use Hiraeth\Application;
use Hiraeth\Routing\RouterInterface;
use Hiraeth\Routing\ResolverInterface;

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
	 *
	 */
	protected $app = NULL;


	/**
	 *
	 */
	protected $resolver = NULL;


	/**
	 *
	 */
	protected $responseFactory = NULL;


	/**
	 *
	 */
	protected $router = NULL;


	/**
	 *
	 */
	public function __construct(Application $app, RouterInterface $router, ResolverInterface $resolver, ResponseFactory $response_factory)
	{
		$this->app             = $app;
		$this->router          = $router;
		$this->resolver        = $resolver;
		$this->responseFactory = $response_factory;
	}


	/**
	 *
	 */
	public function process(Request $request, RequestHandler $handler): Response
	{
		$response = $this->responseFactory->createResponse(200);
		$route    = $this->router->match($request, $response);

		return $this->resolver->run($route, $request, $response);
	}
}
