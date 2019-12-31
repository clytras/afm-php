<?php

require(__DIR__.'/../vendor/autoload.php');

use function Lytrax\AFM\validateAFM;
use function Lytrax\AFM\generateValidAFM;

foreach([
  '(default)' => [],
  'pre99' => ['pre99' => true],
  'legalEntity' => ['legalEntity' => true],
  'individual' => ['individual' => true],
  'repeatTolerance:0' => ['repeatTolerance' => 0]
] as $type => $params) {
  $afm = generateValidAFM($params);
  $validation = validateAFM($afm) ? '(valid)' : '(invalid)';
  echo "{$type} {$afm} {$validation}".PHP_EOL;
}
