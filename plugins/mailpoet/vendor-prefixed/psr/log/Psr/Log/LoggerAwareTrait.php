<?php
namespace MailPoetVendor\Psr\Log;
if (!defined('ABSPATH')) exit;
trait LoggerAwareTrait
{
 protected $logger;
 public function setLogger(LoggerInterface $logger)
 {
 $this->logger = $logger;
 }
}
