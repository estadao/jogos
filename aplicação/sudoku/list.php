<?php

print_r('<pre>');

$list = [];

$year = date('Y');
$month = date('m');

$years = glob("data/$year/$month/*", GLOB_ONLYDIR);

rsort($years);

foreach ($years as $lalala) {
  array_push($list, $lalala);
}

$first = mktime(0, 0, 0, 2, 16, 2017);
$timestamp = mktime(0, 0, 0, $month, 1, $year);

while (count($list) < 45 && $timestamp >= $first) {
  $timestamp = mktime(0, 0, 0, $month - 1, 1, $year);

  $year = date('Y', $timestamp);
  $month = date('m', $timestamp);

  $years = glob("data/$year/$month/*", GLOB_ONLYDIR);

  rsort($years);

  foreach ($years as $lalala) {
    array_push($list, $lalala);
  }

  echo date('Y-m', $timestamp) . "\n";
}

// date('Y-m-d', mktime(0, 0, 0, 02 - 15, 01, 2019))
print_r($list);
print_r('</pre>');
