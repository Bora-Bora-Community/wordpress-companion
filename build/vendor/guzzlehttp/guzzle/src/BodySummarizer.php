<?php

namespace _PhpScoper9a3678ae6a12\GuzzleHttp;

use _PhpScoper9a3678ae6a12\Psr\Http\Message\MessageInterface;
/** @internal */
final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;
    public function __construct(int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \_PhpScoper9a3678ae6a12\GuzzleHttp\Psr7\Message::bodySummary($message) : \_PhpScoper9a3678ae6a12\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
