<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelIdColumnCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('campaigns', 'label_id') && !Schema::hasColumn('campaigns', 'theme_id')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->unsignedBigInteger('label_id')->nullable();
                $table->enum('theme_id', array('1','2','3'))->default('1');
                $table->foreign('label_id')->references('id')->on('label_settings')->nullable();
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
        //
    }
}
