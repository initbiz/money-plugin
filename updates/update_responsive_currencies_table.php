<?php namespace Initbiz\CumulusSubscriptions\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateResponsiveCurrenciesTable extends Migration
{
    public function up()
    {
        Schema::table('responsiv_currency_currencies', function ($table) {
            $table->integer('fraction_digits')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::table('responsiv_currency_currencies', function ($table) {
            $table->dropColumn('fraction_digits');
        });
    }
}
