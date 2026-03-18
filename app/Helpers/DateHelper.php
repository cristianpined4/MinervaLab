<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
  /**
   * Formatear fecha a DD/MM/YYYY
   * 
   * @param mixed $date
   * @return string
   */
  public static function formatDate($date): string
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

  /**
   * Formatear hora a HH:MM AM/PM
   * 
   * @param mixed $time
   * @return string
   */
  public static function formatTime($time): string
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

  /**
   * Formatear fecha y hora a DD/MM/YYYY HH:MM AM/PM
   * 
   * @param mixed $datetime
   * @return string
   */
  public static function formatDateTime($datetime): string
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

  /**
   * Obtener fecha mínima seleccionable (hoy)
   * 
   * @return string
   */
  public static function getMinDate(): string
  {
    return Carbon::today()->format('Y-m-d');
  }

  /**
   * Validar que una fecha no sea pasada
   * 
   * @param mixed $date
   * @return bool
   */
  public static function isPastDate($date): bool
  {
    try {
      return Carbon::parse($date)->startOfDay()->lt(Carbon::today());
    } catch (\Exception $e) {
      return false;
    }
  }
}
