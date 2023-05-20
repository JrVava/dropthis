<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelesaeClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('release_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('release_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email')->nullable();
            $table->text('uri')->nullable();
            $table->text('host')->nullable();
            $table->text('referer')->nullable();
            $table->text('is_first_click');
            $table->text('is_robot');
            $table->text('user_agent')->nullable();
            $table->text('os')->nullable();
            $table->text('device')->nullable();
            $table->text('browser_type')->nullable();
            $table->text('browser_version')->nullable();
            $table->text('music_platform')->nullable();
            $table->text('visitor_id')->nullable();
            $table->text('country')->nullable();
            $table->text('ip')->nullable();
            $table->timestamps();
            $table->foreign('release_id')->references('id')->on('releases');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relesae_clicks');
    }
}
