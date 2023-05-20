<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddGroupFieldCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaigns', 'email_group')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->string('email_group')->nullable();
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
        if(Schema::hasColumn('campaigns', 'email_group')){
            Schema::table('campaigns', function (Blueprint $table) {
                $table->dropColumn('email_group');
            });
        }
    }
}
