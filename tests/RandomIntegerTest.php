<?php

use PHPUnit\Framework\TestCase;

use function Lytrax\AFM\validateAFM;

require('helpers.php');
require_once(__DIR__.'/../src/utils.php'); // For testing getRandomInt


/**
 * @testdox Random Integer
 */
class RandomIntegerTest extends TestCase
{
  /**
   * @testdox Generate integers between 0 and 9
   */
  public function testGenerateIntegersWithRange()
  {
    global $Iterations;
    for($i = 0; $i < $Iterations; $i++) {
      $value = getRandomInt(0, 9);
      $this->assertIsInt($value);
      $this->assertGreaterThanOrEqual(0, $value);
      $this->assertLessThanOrEqual(9, $value);
    }
  }

    /**
   * @testdox Generate integers between 0 and 9 excluding specific digits
   */
  public function testGenerateIntegersWithRangeExcludingSpecificDigits()
  {
    global $Iterations;
    $digits = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    for($i = 0; $i < $Iterations; $i++) {
      foreach($digits as $notEqual) {
        $value = getRandomInt(0, 9, $notEqual);
        $this->assertIsInt($value);
        $this->assertGreaterThanOrEqual(0, $value);
        $this->assertLessThanOrEqual(9, $value);
        $this->assertNotEquals($notEqual, $value);
      }
    }
  }
}

// ./vendor/bin/phpunit --testdox
