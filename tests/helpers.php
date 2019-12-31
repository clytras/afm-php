<?php

$StaticValidNumbers = [
  '090000045', // DEI
  '094019245', // OTE
  '094079101', // EYDAP
];

$StaticInvalidNumbers = [
  '123456789',
  '097364585',
  '150663780'
];

$InvalidErrors = [
  'length' => '09000004',
  'nan' => '09000004A',
  'zero' => '000000000',
  'invalid' => '123456789'
];

$Iterations = 10;
