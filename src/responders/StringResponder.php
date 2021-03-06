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
	 * {@inheritDoc}
	 */
	public function __invoke(Resolver $resolver): Response
	{
		$finfo     = finfo_open();
		$result    = $resolver->getResult();
		$response  = $resolver->getResponse();
		$stream    = $this->streamFactory->createStream($result);
		$mime_type = finfo_buffer($finfo, $result, FILEINFO_MIME_TYPE);

		finfo_close($finfo);

		return $response
			->withStatus(200)
			->withBody($stream)
			->withHeader('Content-Type', $mime_type)
			->withHeader('Content-Length', $stream->getSize())
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
