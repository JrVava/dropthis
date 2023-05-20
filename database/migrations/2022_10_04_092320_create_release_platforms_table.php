<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_platforms', function (Blueprint $table) {
            $table->id();
            $table->text('code')->nullable();
            $table->text('url')->nullable();
            $table->unsignedBigInteger('release_id')->nullable();
            $table->timestamps();
            $table->foreign('release_id')->references('id')->on('releases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('release_platforms');
    }
}
