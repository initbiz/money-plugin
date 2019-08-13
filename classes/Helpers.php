<?php declare(strict_types=1);

namespace Initbiz\Money\Classes;

use Responsiv\Currency\Models\Currency;
use Responsiv\Currency\Helpers\Currency as CurrencyHelper;

class Helpers
{
    /**
     * Removes all non numeric characters from string
     * @param  string $value String with bloat
     * @return string        clear number string
     */
    public static function removeNonNumeric(String $value): String
    {
        $value = $value;
        $value = preg_replace('/[^0-9]|\s/', "", $value);
        return $value;
    }

    /**
     * Format money using integer amount and currencyCode format definition
     *
     * @param int $amount
     * @param string $currencyCode
     * @return string
     */
    public static function formatMoney(int $amount, string $currencyCode): String
    {
        $currency = Currency::findByCode($currencyCode);
        
        $fractionDigits = $currency->initbiz_money_fraction_digits;

        $currencyHelper = new CurrencyHelper();

        $value = $currencyHelper->format($amount / pow(10, $fractionDigits));

        return $value;
    }

    public static function formatMoneyBoth(array $params): String
    {
        $value = self::formatMoney($params['amount'], $params['currency']);

        return $value;
    }
}
