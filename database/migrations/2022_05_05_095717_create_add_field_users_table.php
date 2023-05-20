<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'user_role')){
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_role')->default(true);
            });
        }

        \DB::statement("UPDATE users SET user_role='admin' WHERE id=1");
        \DB::statement("UPDATE users SET user_role='user' WHERE id > 1");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('users', 'user_role')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_role');
            });
        }
        //Schema::dropIfExists('add_field_users');
    }
}
