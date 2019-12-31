<?php

function getRandomInt(int $min, int $max, int $notEqual = null): int {
  $result = null;

  do {
    $result = mt_rand($min, $max);
  } while($notEqual !== null && $result === $notEqual);

  return $result;
}

function lengthOf($str) {
  return function_exists('mb_strlen') ? 
    mb_strlen($str) : 
    strlen($str);
}
