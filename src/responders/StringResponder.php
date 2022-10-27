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
		$result    = $resolver->getResult();
		$response  = $resolver->getResponse();
		$stream    = $this->streamFactory->createStream($result);

		if ($finfo = finfo_open()) {
			$mime_type = finfo_buffer($finfo, $result, FILEINFO_MIME_TYPE);
			finfo_close($finfo);
		}

		if (empty($mime_type)) {
			$mime_type = 'text/plain';
		}

		return $response
			->withStatus(200)
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
