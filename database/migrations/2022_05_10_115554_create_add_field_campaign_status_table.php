<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldCampaignStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaigns', 'campaign_status')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('campaign_status')->nullable();
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
        if(Schema::hasColumn('campaigns', 'campaign_status')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('campaign_status');
            });
        }
    }
}
