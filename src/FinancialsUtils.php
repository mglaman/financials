<?php

namespace Drupal\financials;

class FinancialsUtils {

  public static function currencyFormat($priceAmount) {
    return commerce_currency_format($priceAmount, commerce_default_currency(), null, true);
  }

  public static function priceFieldAmount(\EntityStructureWrapper $priceField) {
    return $priceField->get('amount')->value();
  }

  public static function priceFieldDiff(\EntityStructureWrapper $left, \EntityStructureWrapper $right) {
    return self::priceFieldAmount($left) - self::priceFieldAmount($right);
  }

  public static function diffGoodOrBad($accountType, $diff) {
    if ($accountType) {
      return ($diff > 0);
    }
    else {
      return ($diff < 0);
    }
  }
}