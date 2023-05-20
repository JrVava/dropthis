<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldTrackReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('releases', 'track')) {
            Schema::table('releases', function (Blueprint $table) {
                $table->string('track')->nullable();
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
        if(Schema::hasColumn('releases', 'track')){
            Schema::table('releases', function (Blueprint $table) {
                $table->dropColumn('track');
            });
        }
    }
}
