<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldPlatfomIdPlatformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('release_clicks', 'platform_id')) {
            Schema::table('release_clicks', function (Blueprint $table) {
                $table->unsignedBigInteger('platform_id')->nullable();
                $table->foreign('platform_id')->references('id')->on('release_platforms');
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
        if(Schema::hasColumn('release_clicks', 'platform_id')){
            Schema::table('release_clicks', function (Blueprint $table) {
                $table->dropColumn('platform_id');
            });
        }
    }
}
