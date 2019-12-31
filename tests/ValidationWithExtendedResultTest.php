<?php

use PHPUnit\Framework\TestCase;

use function Lytrax\AFM\validateAFM;

require('helpers.php');


/**
 * @testdox Validation with extendedResult
 */
class ValidationWithExtendedResultTest extends TestCase
{
  public function testValidateValidAfmNumbers()
  {
    global $StaticValidNumbers;
    foreach($StaticValidNumbers as $afm) {
      $result = validateAFM($afm, ['extendedResult' => true]);
      $this->assertIsArray($result);
      $this->assertArrayHasKey('valid', $result);
      $this->assertArrayNotHasKey('error', $result);
      $this->assertTrue($result['valid']);
    }
  }

  public function testInvalidateInvalidAfmNumbers()
  {
    global $StaticInvalidNumbers;
    foreach($StaticInvalidNumbers as $afm) {
      $result = validateAFM($afm, ['extendedResult' => true]);
      $this->assertIsArray($result);
      $this->assertArrayHasKey('valid', $result);
      $this->assertArrayHasKey('error', $result);
      $this->assertFalse($result['valid']);
      $this->assertEquals('invalid', $result['error']);
    }
  }

  public function testInvalidateLengthError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['length'];
    $result = validateAFM($afm, ['extendedResult' => true]);
    $this->assertIsArray($result);
    $this->assertArrayHasKey('valid', $result);
    $this->assertArrayHasKey('error', $result);
    $this->assertFalse($result['valid']);
    $this->assertEquals('length', $result['error']);
  }

  public function testInvalidateNanError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['nan'];
    $result = validateAFM($afm, ['extendedResult' => true]);
    $this->assertIsArray($result);
    $this->assertArrayHasKey('valid', $result);
    $this->assertArrayHasKey('error', $result);
    $this->assertFalse($result['valid']);
    $this->assertEquals('nan', $result['error']);
  }

  public function testInvalidateZeroError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['zero'];
    $result = validateAFM($afm, ['extendedResult' => true]);
    $this->assertIsArray($result);
    $this->assertArrayHasKey('valid', $result);
    $this->assertArrayHasKey('error', $result);
    $this->assertFalse($result['valid']);
    $this->assertEquals('zero', $result['error']);
  }

  public function testInvalidateInvalidError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['invalid'];
    $result = validateAFM($afm, ['extendedResult' => true]);
    $this->assertIsArray($result);
    $this->assertArrayHasKey('valid', $result);
    $this->assertArrayHasKey('error', $result);
    $this->assertFalse($result['valid']);
    $this->assertEquals('invalid', $result['error']);
  }
}

// ./vendor/bin/phpunit --testdox
