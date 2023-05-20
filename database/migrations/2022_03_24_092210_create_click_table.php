<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('click', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('links_id');
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
            $table->text('visitor_id')->nullable();
            $table->text('country')->nullable();
            $table->text('ip')->nullable();
            $table->timestamps();

            $table->foreign('links_id')->references('id')->on('links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('click');
    }
}
