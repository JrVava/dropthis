<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('created_by_id');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('link_id')->references('id')->on('links');
            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links_groups');
    }
}
