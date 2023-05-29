<?php

namespace Automattic\WooCommerce\GoogleListingsAndAds\Vendor\GuzzleHttp;

use Psr\Http\Message\MessageInterface;

interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string;
}
