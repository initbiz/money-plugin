# Introduction
Everything you need to have installed to work with money in your OctoberCMS app.

Money is always expressed with two factors. It is value (amount) and a currency. Sometimes the latter is default but it always is defined.

# Documentation

The plugin installs [Brick/Money](https://github.com/brick/money) library as a requirement.

## Key notes
Amounts should always be stored in DB using integer or other monetary types. I prefer storing it using integer types.

## MoneyFields behavior
The behavior uses `$moneyFields` attribute.

## Backend form widget
The plugins adds backend form widget called money.

It is designed to input money made of two factors: amount and currency. By default you will get two fields:

* Amount input which gets parsed every time you refresh page and input a value (using currency)
* Select with currencies defined in [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency).

### Mode amount
The widget gives you the ability to input amount and use only the default currency.
