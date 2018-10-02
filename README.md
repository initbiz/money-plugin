# Introduction
Everything you need to have installed to work with money in your OctoberCMS app.

Money is always expressed with two factors. It is value (amount) and a currency. Sometimes the latter is default but it always is defined.

# Documentation

The plugin installs [Brick/Money](https://github.com/brick/money) library as a requirement.

## Key notes
Amounts should always be stored in DB using integer or other monetary types. I prefer storing it using integer types.

## Backend form widget
If you want to use the form widget you have to have defined two columns for every money type field.

After that you have to write your own mutator and accessor or use `MoneyFields` behavior described below.
The plugins adds backend form widget called money.

It is designed to input money made of two factors: amount and currency. By default you will get two fields:

* Amount input which gets parsed every time you refresh page and input a value (using currency)
* Select with currencies defined in [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency).

### Mode amount
The widget gives you the ability to input amount and use only the default currency.

## MoneyFields behavior
The behavior uses `$moneyFields` attribute to add dynamically mutators and accessors for money fields.

Using the bevarior looks as in the example:

```php
    class Product extends Model
    {
        protected $moneyFields = [
            'price' => [
                'amountColumn' => 'amount',
                'currencyIdColumn' => 'currency_id'
            ]
        ];
        //...
        public $implement = [
            'Initbiz.Money.Behaviors.MoneyFields'
        ];
        //...
    }
```

This code will dynamically add two methods to `Product` model: `setPriceAttribute` and `getPriceAttribute`. Both of them will be using `amount` as an amount column name (and model attribute) and `currency_id` as a currency column name (and model attribute as well).

If you want to have only one currency in your model there should not be problems with using the same column name for more than one field.
