<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddEmailFieldMultipleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Review Mapping Sechema
        if(Schema::hasColumn('review_mapping', 'user_id')){
            Schema::table('review_mapping', function (Blueprint $table) {
                $table->dropForeign('review_mapping_user_id_foreign');
                $table->dropColumn('user_id');
            });
                
            Schema::table('review_mapping', function (Blueprint $table) {

                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
        // Campaign Click Sechema

        if(Schema::hasColumn('campaign_clicks', 'user_id')){
            Schema::table('campaign_clicks', function (Blueprint $table) {
                $table->dropForeign('campaign_clicks_user_id_foreign');
                $table->dropColumn('user_id');
            });
            Schema::table('campaign_clicks', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email')->nullable();

                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        // Download Mapping Sechema

        if(Schema::hasColumn('download_mapping', 'user_id')){
            Schema::table('download_mapping', function (Blueprint $table) {
                $table->dropForeign('download_mapping_user_id_foreign');
                $table->dropColumn('user_id');
            });

            Schema::table('download_mapping', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email')->nullable();
                
                $table->foreign('user_id')->references('id')->on('users');
            });
        }

        if(Schema::hasColumn('reviews_feedbacks', 'user_id')){
            Schema::table('reviews_feedbacks', function (Blueprint $table) {
                $table->dropForeign('reviews_feedbacks_user_id_foreign');
                $table->dropColumn('user_id');
            });
            Schema::table('reviews_feedbacks', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable();                
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
        Schema::table('review_mapping', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
          });
    }
}
