<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddUnsubcriptionFieldEmailGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('email_groups', 'unsubscription')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->boolean('unsubscription')->nullable()->default(0);
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
        if(Schema::hasColumn('email_groups', 'unsubscription')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->dropColumn('unsubscription');
            });
        }
    }
}
