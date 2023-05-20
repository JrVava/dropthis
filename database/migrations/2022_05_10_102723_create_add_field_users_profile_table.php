<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('users', 'website') && !Schema::hasColumn('users', 'logo') && !Schema::hasColumn('users', 'bg_color') && !Schema::hasColumn('users', 'bg_image')){
            Schema::table('users', function (Blueprint $table) {
                $table->string('website')->nullable();
                $table->string('logo')->nullable();
                $table->string('bg_color')->nullable();
                $table->string('bg_image')->nullable();
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
        if(Schema::hasColumn('users', 'website') && Schema::hasColumn('users', 'logo') && Schema::hasColumn('users', 'bg_color')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('website');
                $table->dropColumn('logo');
                $table->dropColumn('bg_color');
                $table->dropColumn('bg_image');
            });
        }
    }
}
