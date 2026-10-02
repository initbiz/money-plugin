<?php

declare(strict_types=1);

namespace Initbiz\Money\Classes;

use Responsiv\Currency\Models\Currency;
use Responsiv\Currency\Helpers\Currency as CurrencyHelper;

class Helpers
{
    /**
     * Removes all non numeric characters from string
     * @param  string $value string with bloat
     * @return string        clear number string
     */
    public static function removeNonNumeric(string $value): string
    {
        return preg_replace('/[^0-9]|\s/', "", $value);
    }

    /**
     * Format money using integer amount and currencyCode format definition
     *
     * @param int $amount
     * @param string $currencyCode
     * @return string
     */
    public static function formatMoney(int $amount, string $currencyCode): string
    {
        $currencyHelper = new CurrencyHelper();

        return $currencyHelper->format(self::formatAmountDot($amount, $currencyCode), ['in' => $currencyCode]);
    }

    /**
     * Return amount in float format (with dot separator)
     *
     * @param integer $amount
     * @param string $currencyCode
     * @return string
     */
    public static function formatAmountDot(int $amount, string $currencyCode): string
    {
        $currency = Currency::findByCode($currencyCode);
        $fractionDigits = (int) ($currency->initbiz_money_fraction_digits ?? 2);
        $dotAmount = $amount / pow(10, $fractionDigits);
        return number_format($dotAmount, $fractionDigits, ".", "");
    }

    /**
     * Format money using array where
     * [
     *    'amount' => <amount>,
     *    'currency' => <currency_code>,
     * ]
     *
     * @param array $params
     * @return string
     */
    public static function formatMoneyBoth(?array $params): string
    {
        if (empty($params)) {
            return '';
        }

        return self::formatMoney((int) $params['amount'], $params['currency']);
    }

    /**
     * Return amount in float format (with dot separator) using array where
     * [
     *    'amount' => <amount>,
     *    'currency' => <currency_code>,
     * ]
     *
     * @param array $params
     * @return string
     */
    public static function formatAmountDotBoth(array $params): string
    {
        if (empty($params)) {
            return '';
        }

        return self::formatAmountDot($params['amount'], $params['currency']);
    }

    /**
     * Get amount in integer from dot format
     * e.g. $12 will return 1200, $12.5 -> 1250, $12.34 -> 1234
     *
     * @param float $amount
     * @param string $currencyCode
     * @return integer
     */
    public static function intFromFloat(float $amount, string $currencyCode): int
    {
        $currency = Currency::findByCode($currencyCode);
        $fractionDigits = $currency->initbiz_money_fraction_digits;
        $intAmount = $amount * pow(10, $fractionDigits);
        return (int) round($intAmount);
    }
}
