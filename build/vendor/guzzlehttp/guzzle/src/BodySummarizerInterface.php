<?php

namespace _PhpScoper9a3678ae6a12\GuzzleHttp;

use _PhpScoper9a3678ae6a12\Psr\Http\Message\MessageInterface;
/** @internal */
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string;
}
