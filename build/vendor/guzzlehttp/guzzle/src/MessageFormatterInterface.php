<?php

namespace _PhpScoper9a3678ae6a12\GuzzleHttp;

use _PhpScoper9a3678ae6a12\Psr\Http\Message\RequestInterface;
use _PhpScoper9a3678ae6a12\Psr\Http\Message\ResponseInterface;
/** @internal */
interface MessageFormatterInterface
{
    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null        $error    Exception that was received
     */
    public function format(RequestInterface $request, ResponseInterface $response = null, \Throwable $error = null) : string;
}
