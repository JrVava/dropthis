<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'can_submit_feedbacks')){
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('can_submit_feedbacks')->default(true);
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
        if(Schema::hasColumn('users', 'can_submit_feedbacks')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('can_submit_feedbacks');
            });
        }
        //Schema::dropIfExists('add_fields_users');
    }
}
