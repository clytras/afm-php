<?php

namespace Lytrax\AFM;

require_once('utils.php');

/**
 * Checks if the passed AFM is a valid AFM number
 * @param string $afm A string to be check if it's a valid AFM.
 * @param bool $params.extendedResult (optional) A boolean result or an assoc array indicating the validation of the number.
 * @return bool|array A boolean result or an assoc array indicating the validation of the number.
 */
function validateAFM(string $afm, array $params = [])
{
  $extendedResult = $params['extendedResult'] ?? false;

  if(lengthOf($afm) !== 9) {
    return $extendedResult ? [
      'valid' => false,
      'error' => 'length'
    ] : false;
  }

  if(!preg_match('/^\d+$/', $afm)) {
    return $extendedResult ? [
      'valid' => false,
      'error' => 'nan'
    ] : false;
  }

  if($afm === str_repeat('0', 9)) {
    return $extendedResult ? [
      'valid' => false,
      'error' => 'zero'
    ] : false;
  }

  $body = substr($afm, 0, -1);
  $chars = str_split($body);
  $i = 0;
  $sum = array_reduce($chars, function($s, $v) use (&$i) {
    return $s + (intval($v) << (8 - $i++));
  }, 0);

  $calc = $sum % 11;
  $d9 = intval($afm[8]);
  $valid = $calc % 10 === $d9;

  if($extendedResult) {
    return $valid ?
      ['valid' => $valid] :
      ['valid' => $valid, 'error' => 'invalid'];
  }

  return $valid;
}


/**
 * Generates an AFM number based on object parameters
 * @param null|int $params.forceFirstDigit (optional) - If specified, overrides all pre99, legalEntity and individual.
 * @param bool $params.pre99=false (optional) - Για ΑΦΜ πριν από 1/1/1999 (ξεκινάει με 0), (if true, overrides both legalEntity and individual).
 * @param bool $params.individual=false (optional) - Φυσικά πρόσωπα, (ξεκινάει με 1-4)
 * @param bool $params.legalEntity=false (optional) - Νομικές οντότητες (ξεκινάει με 7-9)
 * @param null|int $params.repeatTolerance (optional) - Number for max repeat tolerance (0 for no repeats, unspecified for no check)
 * @param bool $params.valid=true (optional) - Generate valid or invalid AFM
 * @return string - A valid or invalid 9 digit AFM number
 */
function generateAFM(array $params = []): string
{
  $forceFirstDigit = $params['forceFirstDigit'] ?? null;
  $pre99 = $params['pre99'] ?? false;
  $individual = $params['individual'] ?? false;
  $legalEntity = $params['legalEntity'] ?? false;
  $repeatTolerance = $params['repeatTolerance'] ?? null;
  $valid = $params['valid'] ?? true;

  $min = $legalEntity ? 7 : 1;
  $max = $individual ? 4 : 9;
  $repeatOf = !$repeatTolerance && $repeatTolerance !== 0 ? null : $repeatTolerance;
  $digit = $forceFirstDigit !== null && is_numeric($forceFirstDigit) ?
    $forceFirstDigit :
    ($pre99 ?
      0 :
      getRandomInt($min, $max)
    );
  $lastGenDigit = $digit;
  $repeats = 0;
  $body = $digit;
  $sum = $digit * 0x100;

  for($i = 7; $i >= 1; $i--) {
    $digit = getRandomInt(0, 9, $repeatOf !== null && $repeats >= $repeatOf ? $lastGenDigit : null);
    $body .= $digit;
    $sum += $digit << $i;
    if($digit === $lastGenDigit) {
      $repeats++;
    } else {
      $repeats = 0;
    }
    $lastGenDigit = $digit;
  }

  $validator = $sum % 11;
  $d9Valid = $validator % 10;
  $d9 = $valid ? $d9Valid : getRandomInt(0, 9, $d9Valid);

  return $body.$d9;
}

/**
 * Generates a valid AFM number based on object parameters
 * @param null|int $params.forceFirstDigit (optional) - If specified, overrides all pre99, legalEntity and individual.
 * @param bool $params.pre99=false (optional) - Για ΑΦΜ πριν από 1/1/1999 (ξεκινάει με 0), (if true, overrides both legalEntity and individual).
 * @param bool $params.individual=false (optional) - Φυσικά πρόσωπα, (ξεκινάει με 1-4)
 * @param bool $params.legalEntity=false (optional) - Νομικές οντότητες (ξεκινάει με 7-9)
 * @param null|int $params.repeatTolerance (optional) - Number for max repeat tolerance (0 for no repeats, unspecified for no check)
 * @return string - A valid 9 digit AFM number
 */
function generateValidAFM(array $params = []): string
{
  return generateAFM(array_merge($params, ['valid' => true]));
}

/**
 * Generates an invalid AFM number based on object parameters
 * @param null|int $params.forceFirstDigit (optional) - If specified, overrides all pre99, legalEntity and individual.
 * @param bool $params.pre99=false (optional) - Για ΑΦΜ πριν από 1/1/1999 (ξεκινάει με 0), (if true, overrides both legalEntity and individual).
 * @param bool $params.individual=false (optional) - Φυσικά πρόσωπα, (ξεκινάει με 1-4)
 * @param bool $params.legalEntity=false (optional) - Νομικές οντότητες (ξεκινάει με 7-9)
 * @param null|int $params.repeatTolerance (optional) - Number for max repeat tolerance (0 for no repeats, unspecified for no check)
 * @return string - An invalid 9 digit AFM number
 */
function generateInvalidAFM(array $params = []): string
{
  return generateAFM(array_merge($params, ['valid' => false]));
}
