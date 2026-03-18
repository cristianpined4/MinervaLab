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
