# Greek TIN/AFM Validator and Generator

[![Linux Build Status](https://img.shields.io/travis/clytras/afm-php.svg?style=flat)](https://travis-ci.org/clytras/afm-php.svg?branch=master)

Validate and generate Greek TIN (*Tax Identification Number*) / AFM (*Αριθμός Φορολογικού Μητρώου*). Generation function can create valid or invalid numbers including parameters for old format, individuals, legal entities and repet tolerance digits control.

## Online demo and presentation

https://lytrax.io/blog/projects/greek-tin-validator-generator

## Installation

```
composer require lytrax/afm
```

## Usage

Use one or more functions:

```php
use function Lytrax\AFM\validateAFM;
use function Lytrax\AFM\generateAFM;
use function Lytrax\AFM\generateValidAFM;
use function Lytrax\AFM\generateInvalidAFM;
```

Validate a number:

```php
validateAFM('090000045');
bool(true)

validateAFM('123456789');
bool(false)
```

Generate a valid number:

```php
generateValidAFM();
string(9) "731385437"
```

Generate an invalid number:

```php
generateInvalidAFM();
string(9) "853003357"
```

## API

**validateAFM** `($afm, $params = [])`
* `$afm: string` - A number to be checked
* `$params: array (optional)` - Optional associative array for named parameters
* `$params['extendedResult']: boolean = false` - Return a `boolean` or `array`
* **Returns**: `boolean` or `array` (Associative array with `valid: boolean` and `error: string ('length' or 'nan' or 'zero' or 'invalid')`)

Example:
```php
validateAFM('ab1234', ['extendedResult' => true]);
array(2) {
  ["valid"]=>
  bool(false)
  ["error"]=>
  string(6) "length"
}
```

**generateAFM** `($params = [])`
* `$params: array (optional)` - Optional associative array for named parameters
* `$params['forceFirstDigit']: null|int (optional)` - If specified, overrides all pre99, legalEntity and individual
* `$params['pre99']: boolean = false` - Για ΑΦΜ πριν από 1/1/1999 (ξεκινάει με 0), (if true, overrides both legalEntity and individual)
* `$params['individual']: boolean = false` - Φυσικά πρόσωπα, (ξεκινάει με 1-4)
* `$params['legalEntity']: boolean = false` - Νομικές οντότητες (ξεκινάει με 7-9)
* `$params['repeatTolerance']: null|int (optional)` - Number for max repeat tolerance (0 for no repeats, unspecified for no check)
* `$params['valid']: boolean = true` - Generate valid or invalid AFM
* **Returns**: `string` - A valid or invalid 9 digit AFM number

Example:
```php
generateAFM([
  'forceFirstDigit' => 3,
  'repeatTolerance' => 1,
  'valid' => true
]);
string(9) "335151580"
```

**generateValidAFM** - Same as `generateAFM` with `$params['valid']` force and override to `true`
* **Returns**: `string` - A valid 9 digit AFM number

Example:
```php
generateValidAFM(['pre99' => true]);
string(9) "070825250"
```

**generateInvalidAFM** - Same as `generateAFM` with `$params['valid']` force and override to `false`
* **Returns**: `string` - An invalid 9 digit AFM number

Example:
```php
generateInvalidAFM(['legalEntity' => true]);
string(9) "877577341"
```

## Test

Clone this repository, intall packages and run PHPUnit:

```
git clone https://github.com/clytras/afm-php.git && cd afm-php
composer install
./vendor/bin/phpunit --testdox
```

## Changelog

See [CHANGELOG](https://github.com/clytras/afm-php/blob/master/CHANGELOG.md)

## License

MIT License - see the [LICENSE](https://github.com/clytras/afm-php/blob/master/LICENSE) file for details
