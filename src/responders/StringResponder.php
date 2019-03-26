<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 *
 */
class StringResponder implements ResponderInterface
{
	/**
	 *
	 */
	protected $streamFactory = NULL;


	/**
	 *
	 */
	public function __construct(StreamFactory $stream_factory)
	{
		$this->streamFactory = $stream_factory;
	}


	/**
	 *
	 */
	public function __invoke(Resolver $resolver): Response
	{
		return $resolver->getResponse()->withBody(
			$this->streamFactory->createStream($resolver->getResult())
		);
	}


	/**
	 *
	 */
	public function match(Resolver $resolver): bool
	{
		return is_string($resolver->getResult());
	}
}
