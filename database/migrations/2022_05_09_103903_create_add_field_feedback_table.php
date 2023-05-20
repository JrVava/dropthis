<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddFieldFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if(!Schema::hasColumn('reviews_feedbacks', 'ip') && !Schema::hasColumn('reviews_feedbacks', 'country')){
            Schema::table('reviews_feedbacks', function (Blueprint $table) {
                $table->string('ip');
                $table->string('country');
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
        if(Schema::hasColumn('reviews_feedbacks', 'ip') && Schema::hasColumn('reviews_feedbacks', 'country')){
            Schema::table('reviews_feedbacks', function (Blueprint $table) {
                $table->dropColumn('ip');
                $table->dropColumn('country');
            });
        }
        //Schema::dropIfExists('add_field_feedback');
    }
}
