<?php
namespace MailPoetVendor\Psr\Log;
if (!defined('ABSPATH')) exit;
class NullLogger extends AbstractLogger
{
 public function log($level, $message, array $context = array())
 {
 // noop
 }
}
