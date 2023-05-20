<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldsCampaignTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaigns', 'expire_link_once_downloaded')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->boolean('expire_link_once_downloaded');
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
        if(Schema::hasColumn('campaigns', 'expire_link_once_downloaded')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('expire_link_once_downloaded');
            });
        }
        //Schema::dropIfExists('add_fields_campaign_track');
    }
}
