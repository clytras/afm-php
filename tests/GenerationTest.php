<?php

use PHPUnit\Framework\TestCase;

use function Lytrax\AFM\validateAFM;
use function Lytrax\AFM\generateAFM;
use function Lytrax\AFM\generateValidAFM;
use function Lytrax\AFM\generateInvalidAFM;

require('helpers.php');
require_once(__DIR__.'/../src/utils.php'); // For lengthOf


class GenerationTest extends TestCase
{
  public function testDefault()
  {
    global $Iterations;
    for($i = 0; $i < $Iterations; $i++) {
      $value = generateAFM();
      $valid = validateAFM($value);
      $this->assertTrue($valid);

      $valueValid = generateValidAFM();
      $validValid = validateAFM($valueValid);
      $this->assertTrue($validValid);

      $valueInvalid = generateInvalidAFM();
      $invalidInvalid = validateAFM($valueInvalid);
      $this->assertFalse($invalidInvalid);
    }
  }

  public function testForceFirstDigit()
  {
    global $Iterations;
    $digits = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    for($i = 0; $i < $Iterations; $i++) {
      foreach($digits as $forceFirstDigit) {
        $params = ['forceFirstDigit' => $forceFirstDigit];
        $value = generateAFM($params);
        $valid = validateAFM($value);
        $firstDigit = $value[0];
        $this->assertTrue($valid);
        $this->assertEquals($forceFirstDigit, $firstDigit);
  
        $valueValid = generateValidAFM($params);
        $validValid = validateAFM($valueValid);
        $firstDigitValid = $valueValid[0];
        $this->assertTrue($validValid);
        $this->assertEquals($forceFirstDigit, $firstDigitValid);
  
        $valueInvalid = generateInvalidAFM($params);
        $invalidInvalid = validateAFM($valueInvalid);
        $firstDigitInvalid = $valueInvalid[0];
        $this->assertFalse($invalidInvalid);
        $this->assertEquals($forceFirstDigit, $firstDigitInvalid);
      }
    }
  }

  public function testPre99()
  {
    global $Iterations;
    $params = ['pre99' => true];
    for($i = 0; $i < $Iterations; $i++) {
      $value = generateAFM($params);
      $valid = validateAFM($value);
      $firstDigit = $value[0];
      $this->assertTrue($valid);
      $this->assertEquals($firstDigit, '0');

      $valueValid = generateValidAFM($params);
      $validValid = validateAFM($valueValid);
      $firstDigitValid = $valueValid[0];
      $this->assertTrue($validValid);
      $this->assertEquals($firstDigitValid, '0');

      $valueInvalid = generateInvalidAFM($params);
      $invalidInvalid = validateAFM($valueInvalid);
      $firstDigitInvalid = $valueInvalid[0];
      $this->assertFalse($invalidInvalid);
      $this->assertEquals($firstDigitInvalid, '0');
    }
  }

  public function testIndividual()
  {
    global $Iterations;
    $params = ['individual' => true];
    $re = '/^[1-4]{1}$/';
    for($i = 0; $i < $Iterations; $i++) {
      $value = generateAFM($params);
      $valid = validateAFM($value);
      $firstDigit = $value[0];
      $this->assertTrue($valid);
      $this->assertRegExp($re, $firstDigit);

      $valueValid = generateValidAFM($params);
      $validValid = validateAFM($valueValid);
      $firstDigitValid = $valueValid[0];
      $this->assertTrue($validValid);
      $this->assertRegExp($re, $firstDigitValid);

      $valueInvalid = generateInvalidAFM($params);
      $invalidInvalid = validateAFM($valueInvalid);
      $firstDigitInvalid = $valueInvalid[0];
      $this->assertFalse($invalidInvalid);
      $this->assertRegExp($re, $firstDigitInvalid);
    }
  }

  public function testLegalEntity()
  {
    global $Iterations;
    $params = ['legalEntity' => true];
    $re = '/^[7-9]{1}$/';
    for($i = 0; $i < $Iterations; $i++) {
      $value = generateAFM($params);
      $valid = validateAFM($value);
      $firstDigit = $value[0];
      $this->assertTrue($valid);
      $this->assertRegExp($re, $firstDigit);

      $valueValid = generateValidAFM($params);
      $validValid = validateAFM($valueValid);
      $firstDigitValid = $valueValid[0];
      $this->assertTrue($validValid);
      $this->assertRegExp($re, $firstDigitValid);

      $valueInvalid = generateInvalidAFM($params);
      $invalidInvalid = validateAFM($valueInvalid);
      $firstDigitInvalid = $valueInvalid[0];
      $this->assertFalse($invalidInvalid);
      $this->assertRegExp($re, $firstDigitInvalid);
    }
  }

  public function testRepeatTolerance()
  {
    global $Iterations;
    $re = '/(.)\1+/';
    for($i = 0; $i < $Iterations; $i++) {
      for($repeatTolerance = 0; $repeatTolerance <= 3; $repeatTolerance++) {
        $params = ['repeatTolerance' => $repeatTolerance];

        $value = generateAFM($params);
        $valid = validateAFM($value);
        $body = substr($value, 0, 8);
        $this->assertTrue($valid);

        $re_result = preg_match_all($re, $body, $repeats, PREG_SET_ORDER);
        if($repeatTolerance === 0) {
          $this->assertEquals($re_result, 0);
        } else {
          foreach($repeats as $repeat) {
            $repeatedDigits = $repeat[0];
            $this->assertLessThanOrEqual($repeatTolerance + 1, lengthOf($repeatedDigits));
          }
        }

        $valueValid = generateValidAFM($params);
        $validValid = validateAFM($valueValid);
        $bodyValid = substr($valueValid, 0, 8);
        $this->assertTrue($validValid);

        $re_result = preg_match_all($re, $bodyValid, $repeats, PREG_SET_ORDER);
        if($repeatTolerance === 0) {
          $this->assertEquals($re_result, 0);
        } else {
          foreach($repeats as $repeat) {
            $repeatedDigits = $repeat[0];
            $this->assertLessThanOrEqual($repeatTolerance + 1, lengthOf($repeatedDigits));
          }
        }

        $valueInvalid = generateInvalidAFM($params);
        $invalidInvalid = validateAFM($valueInvalid);
        $bodyInvalid = substr($valueInvalid, 0, 8);
        $this->assertFalse($invalidInvalid);

        $re_result = preg_match_all($re, $bodyInvalid, $repeats, PREG_SET_ORDER);
        if($repeatTolerance === 0) {
          $this->assertEquals($re_result, 0);
        } else {
          foreach($repeats as $repeat) {
            $repeatedDigits = $repeat[0];
            $this->assertLessThanOrEqual($repeatTolerance + 1, lengthOf($repeatedDigits));
          }
        }
      }
    }
  }
}

// ./vendor/bin/phpunit --testdox
