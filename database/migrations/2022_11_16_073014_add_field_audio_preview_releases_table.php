<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAudioPreviewReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('releases', 'audio_preview') &&
         !Schema::hasColumn('releases', 'facebook_url') &&
         !Schema::hasColumn('releases', 'twitter_url') && 
         !Schema::hasColumn('releases', 'youtube_url') && 
         !Schema::hasColumn('releases', 'spotify_url') && 
         !Schema::hasColumn('releases', 'instagram_url') && 
         !Schema::hasColumn('releases', 'soundcloud_url') && 
         !Schema::hasColumn('releases', 'tiktok_url') && 
         !Schema::hasColumn('releases', 'web_url')) {
            Schema::table('releases', function (Blueprint $table) {
                $table->text('audio_preview')->nullable();
                $table->string('facebook_url')->nullable();
                $table->string('twitter_url')->nullable();
                $table->string('youtube_url')->nullable();
                $table->string('spotify_url')->nullable();
                $table->string('instagram_url')->nullable();
                $table->string('soundcloud_url')->nullable();
                $table->string('tiktok_url')->nullable();
                $table->string('web_url')->nullable();
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
