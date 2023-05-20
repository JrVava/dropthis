<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPeakFieldCampaignTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaign_tracks', 'mp3_peak') && !Schema::hasColumn('campaign_tracks', 'wav_peak')){
            Schema::table('campaign_tracks', function (Blueprint $table) {
                $table->text('mp3_peak')->nullable();
                $table->text('wav_peak')->nullable();
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
        if(Schema::hasColumn('campaign_tracks', 'mp3_peak') && Schema::hasColumn('campaign_tracks', 'wav_peak')){
            Schema::table('campaign_tracks', function (Blueprint $table) {
                $table->dropColumn('mp3_peak');
                $table->dropColumn('mp3_peak');
            });
        }
    }
}
