<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPaypalColumnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('orders', 'transaction_id') 
            && !Schema::hasColumn('orders', 'paypal_response')
            && !Schema::hasColumn('orders', 'paypal_status')
            && !Schema::hasColumn('orders', 'transaction_create_time')
            && !Schema::hasColumn('orders', 'transaction_update_time')) {

                Schema::table('orders', function (Blueprint $table) {
                    $table->string('transaction_id')->nullable();
                    $table->text('paypal_response')->nullable();
                    $table->string('paypal_status')->nullable();
                    $table->dateTimeTz('transaction_create_time', $precision = 0);
                    $table->dateTimeTz('transaction_update_time', $precision = 0);
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
        if(Schema::hasColumn('orders', 'transaction_id')
         && Schema::hasColumn('orders', 'paypal_response')
         && Schema::hasColumn('orders', 'paypal_status')
         && Schema::hasColumn('orders', 'transaction_create_time')
         && Schema::hasColumn('orders', 'transaction_update_time')){
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('transaction_id');
                $table->dropColumn('paypal_response');
                $table->dropColumn('paypal_status');
                $table->dropColumn('transaction_create_time');
                $table->dropColumn('transaction_update_time');
            });
        }
    }
}
