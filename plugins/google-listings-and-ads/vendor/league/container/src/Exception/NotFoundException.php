<?php

namespace Automattic\WooCommerce\GoogleListingsAndAds\Vendor\League\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;

class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
}
