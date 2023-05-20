<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldDurationTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaign_tracks', 'mp3_time')){
            Schema::table('campaign_tracks', function (Blueprint $table) {
                $table->text('mp3_time')->nullable();
            });
        }
        if(!Schema::hasColumn('campaign_tracks', 'wav_time')){
            Schema::table('campaign_tracks', function (Blueprint $table) {
                $table->text('wav_time')->nullable();
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
        if(Schema::hasColumn('campaign_tracks', 'mp3_time') && Schema::hasColumn('campaign_tracks', 'wav_time')){
            Schema::table('campaign_tracks', function (Blueprint $table) {
                $table->dropColumn('mp3_time');
                $table->dropColumn('wav_time');
            });
        }
    }
}
