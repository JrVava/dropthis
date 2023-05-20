<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('website');
            $table->string('release_number')->nullable();
            $table->string('cover_artwork')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('leave_rating_and_comment');
            $table->date('release_date')->nullable();
            $table->date('promo_sendout')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
