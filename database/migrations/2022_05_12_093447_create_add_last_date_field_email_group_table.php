<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddLastDateFieldEmailGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('email_groups', 'last_send')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->date('last_send')->nullable();
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
        if(Schema::hasColumn('email_groups', 'last_send')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->dropColumn('last_send');
            });
        }
    }
}
