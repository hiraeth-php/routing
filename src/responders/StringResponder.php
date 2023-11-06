<?php

namespace Hiraeth\Routing;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;

/**
 * {@inheritDoc}
 */
class StringResponder implements Responder
{
	/**
	 * @var StreamFactory|null
	 */
	protected $streams = NULL;


	/**
	 *
	 */
	public function __construct(StreamFactory $streams)
	{
		$this->streams = $streams;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): Response
	{
		$result    = $resolver->getResult();
		$response  = $resolver->getResponse();
		$stream    = $this->streams->createStream($result);
		$mime_type = $resolver->getType($stream);

		return $response
			->withBody($stream)
			->withHeader('Content-Type', $mime_type)
			->withHeader('Content-Length', (string) $stream->getSize())
		;
	}


	/**
	 * {@inheritDoc}
	 */
	public function match(Resolver $resolver): bool
	{
		return is_string($resolver->getResult());
	}
}
