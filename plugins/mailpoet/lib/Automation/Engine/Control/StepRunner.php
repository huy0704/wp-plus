<?php declare(strict_types = 1);

namespace MailPoet\Automation\Engine\Control;

if (!defined('ABSPATH')) exit;


use MailPoet\Automation\Engine\Data\StepRunArgs;
use MailPoet\Automation\Engine\Data\StepValidationArgs;

interface StepRunner {
  public function run(StepRunArgs $runArgs, StepValidationArgs $validationArgs): void;
}
