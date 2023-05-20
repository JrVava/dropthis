<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddNewUserColumnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'new_user')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('new_user')->nullable()->default(true);
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
        if(Schema::hasColumn('users', 'new_user')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('new_user');
            });
        }
    }
}
