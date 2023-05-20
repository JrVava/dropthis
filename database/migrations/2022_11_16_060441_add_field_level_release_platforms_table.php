<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFieldLevelReleasePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('release_platforms', 'level_order')) {
            Schema::table('release_platforms', function (Blueprint $table) {
                $table->integer('level_order')->nullable();
            });
            $platforms = DB::table('release_platforms')->get();
            foreach($platforms as $key => $platform){
                DB::table('release_platforms')->where('id','=',$platform->id)->update([
                    'level_order' => ($key +1),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
