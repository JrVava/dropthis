<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_settings', function (Blueprint $table) {
            $table->id();
            $table->string('label_name')->nullable();
            $table->string('url')->nullable();
            $table->string('email_address')->nullable();
            $table->string('full_company_address')->nullable();
            $table->string('light_version_logo')->nullable();
            $table->string('dark_version_logo')->nullable();
            $table->string('backgroung_image')->nullable();
            $table->boolean('theme_mode')->default(0)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('label_settings');
    }
}
