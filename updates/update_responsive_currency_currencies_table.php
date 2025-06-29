<?php

declare(strict_types=1);

namespace Initbiz\Money\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateResponsiveCurrencyCurrenciesTable extends Migration
{
    public function up()
    {
        Schema::table('responsiv_currency_currencies', function ($table) {
            $table->integer('initbiz_money_fraction_digits')->unsigned()->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('responsiv_currency_currencies', 'initbiz_money_fraction_digits')) {
            Schema::table('responsiv_currency_currencies', function ($table) {
                $table->dropColumn('initbiz_money_fraction_digits');
            });
        }
    }
}
