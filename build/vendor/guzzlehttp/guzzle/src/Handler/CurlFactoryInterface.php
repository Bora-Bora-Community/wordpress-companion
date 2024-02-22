<?php

namespace _PhpScoper9a3678ae6a12\GuzzleHttp\Handler;

use _PhpScoper9a3678ae6a12\Psr\Http\Message\RequestInterface;
/** @internal */
interface CurlFactoryInterface
{
    /**
     * Creates a cURL handle resource.
     *
     * @param RequestInterface $request Request
     * @param array            $options Transfer options
     *
     * @throws \RuntimeException when an option cannot be applied
     */
    public function create(RequestInterface $request, array $options) : EasyHandle;
    /**
     * Release an easy handle, allowing it to be reused or closed.
     *
     * This function must call unset on the easy handle's "handle" property.
     */
    public function release(EasyHandle $easy) : void;
}
