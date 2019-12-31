<?php

use PHPUnit\Framework\TestCase;

use function Lytrax\AFM\validateAFM;

require('helpers.php');


class ValidationTest extends TestCase
{
  public function testValidateValidAfmNumbers()
  {
    global $StaticValidNumbers;
    foreach($StaticValidNumbers as $afm) {
      $result = validateAFM($afm);
      $this->assertTrue($result);
    }
  }

  public function testInvalidateInvalidAfmNumbers()
  {
    global $StaticInvalidNumbers;
    foreach($StaticInvalidNumbers as $afm) {
      $result = validateAFM($afm);
      $this->assertFalse($result);
    }
  }

  public function testInvalidateLengthError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['length'];
    $result = validateAFM($afm);
    $this->assertFalse($result);
  }

  public function testInvalidateNanError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['nan'];
    $result = validateAFM($afm);
    $this->assertFalse($result);
  }

  public function testInvalidateZeroError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['zero'];
    $result = validateAFM($afm);
    $this->assertFalse($result);
  }

  public function testInvalidateInvalidError()
  {
    global $InvalidErrors;
    $afm = $InvalidErrors['invalid'];
    $result = validateAFM($afm);
    $this->assertFalse($result);
  }
}

// ./vendor/bin/phpunit --testdox
