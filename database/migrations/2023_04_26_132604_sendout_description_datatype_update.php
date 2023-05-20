<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SendoutDescriptionDatatypeUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sendouts = DB::table('campaigns')->select('id','description')->get();
        
        if(Schema::hasColumn('campaigns', 'description')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('description');
            });

            Schema::table('campaigns', function (Blueprint $table) {
                $table->text('description')->nullable()->after('release_number');;
            });
            foreach($sendouts as $sendout){
                DB::table('campaigns')->where('id','=',$sendout->id)->update(['description'=>$sendout->description]);
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
