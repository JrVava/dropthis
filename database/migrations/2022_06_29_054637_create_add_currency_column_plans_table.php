<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddCurrencyColumnPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('plans', 'currency')){
            Schema::table('plans', function (Blueprint $table) {
                $table->string('currency');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('add_currency_column_plans');
        if(Schema::hasColumn('plans', 'currency')){
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }
    }
}
