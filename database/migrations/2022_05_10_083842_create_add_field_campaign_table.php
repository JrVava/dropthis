<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaigns', 'user_id')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->default(null);
                $table->foreign('user_id')->references('id')->on('users');
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
        if(Schema::hasColumn('campaigns', 'user_id')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
}
