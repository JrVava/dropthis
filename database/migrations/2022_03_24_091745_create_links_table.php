<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
           $table->id();
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('nofollow');
            $table->tinyInteger('track_me');
            $table->tinyInteger('sponsored');
            $table->tinyInteger('params_forwarding');
            $table->string('params_structure')->nullable();
            $table->string('redirect_type')->nullable();
            $table->tinyInteger('status');
            $table->string('type')->nullable();
            $table->integer('type_id')->nullable();
            $table->string('password')->nullable();
            $table->string('expires_at')->nullable();
            $table->integer('cpt_id')->nullable();
            $table->string('cpt_type')->nullable();
            $table->string('rules')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->integer('updated_by_id');
            $table->timestamps();

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
        Schema::dropIfExists('links');
    }
}
