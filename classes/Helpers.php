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
        $currencyHelper = new CurrencyHelper();

        $value = $currencyHelper->format(self::formatAmountDot($amount, $currencyCode), ['in' => $currencyCode]);

        return $value;
    }

    /**
     * Return amount in float format (with dot separator)
     *
     * @param integer $amount
     * @param string $currencyCode
     * @return String
     */
    public static function formatAmountDot(int $amount, string $currencyCode): String
    {
        $currency = Currency::findByCode($currencyCode);
        
        $fractionDigits = $currency->initbiz_money_fraction_digits;

        $dotAmount = $amount / pow(10, $fractionDigits);

        $dotAmount = (string)$dotAmount;

        return $dotAmount;
    }

    /**
     * Format money using array where 
     * [
     *    'amount' => <amount>,
     *    'currency' => <currency_code>,
     * ]
     *
     * @param array $params
     * @return String
     */
    public static function formatMoneyBoth(?array $params): String
    {
        if (empty($params)) {
            return '';
        }

        $value = self::formatMoney($params['amount'], $params['currency']);

        return $value;
    }

    /**
     * Return amount in float format (with dot separator) using array where
     * [
     *    'amount' => <amount>,
     *    'currency' => <currency_code>,
     * ]
     *
     * @param array $params
     * @return String
     */
    public static function formatAmountDotBoth(array $params): String
    {
        if (empty($params)) {
            return '';
        }

        $value = self::formatAmountDot($params['amount'], $params['currency']);

        return $value;
    }

}
