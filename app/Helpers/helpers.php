<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
  function formatDate($date)
  {
    if (!$date) {
      return '';
    }
    try {
      return Carbon::parse($date)->format('d/m/Y');
    } catch (\Exception $e) {
      return '';
    }
  }
}

if (!function_exists('formatTime')) {
  function formatTime($time)
  {
    if (!$time) {
      return '';
    }
    try {
      return Carbon::parse($time)->format('h:i A');
    } catch (\Exception $e) {
      return '';
    }
  }
}

if (!function_exists('formatDateTime')) {
  function formatDateTime($datetime)
  {
    if (!$datetime) {
      return '';
    }
    try {
      return Carbon::parse($datetime)->format('d/m/Y h:i A');
    } catch (\Exception $e) {
      return '';
    }
  }
}

if (!function_exists('parseDateInput')) {
  function parseDateInput($value)
  {
    if (!$value) {
      return null;
    }

    $value = trim((string) $value);
    $formats = [
      'd/m/Y',
      'Y-m-d',
      'd-m-Y',
      'd.m.Y',
    ];

    foreach ($formats as $format) {
      try {
        $parsed = Carbon::createFromFormat($format, $value);
        if ($parsed !== false) {
          return $parsed->format('Y-m-d');
        }
      } catch (\Exception $e) {
      }
    }

    try {
      return Carbon::parse($value)->format('Y-m-d');
    } catch (\Exception $e) {
      return null;
    }
  }
}

if (!function_exists('parseDateTimeInput')) {
  function parseDateTimeInput($value)
  {
    if (!$value) {
      return null;
    }

    $value = trim((string) $value);
    $formats = [
      'd/m/Y h:i A',
      'd/m/Y h:i:s A',
      'Y-m-d\\TH:i',
      'Y-m-d\\TH:i:s',
      'Y-m-d H:i',
      'Y-m-d H:i:s',
      'd-m-Y h:i A',
    ];

    foreach ($formats as $format) {
      try {
        $parsed = Carbon::createFromFormat($format, $value);
        if ($parsed !== false) {
          return $parsed->format('Y-m-d H:i:s');
        }
      } catch (\Exception $e) {
      }
    }

    try {
      return Carbon::parse($value)->format('Y-m-d H:i:s');
    } catch (\Exception $e) {
      return null;
    }
  }
}

if (!function_exists('getMinDate')) {
  function getMinDate()
  {
    return Carbon::today()->format('Y-m-d');
  }
}

if (!function_exists('isPastDate')) {
  function isPastDate($date)
  {
    try {
      return Carbon::parse($date)->startOfDay()->lt(Carbon::today());
    } catch (\Exception $e) {
      return false;
    }
  }
}
