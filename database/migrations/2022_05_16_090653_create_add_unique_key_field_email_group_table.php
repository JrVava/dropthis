<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateAddUniqueKeyFieldEmailGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('email_groups', 'pass_key')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->string('pass_key')->nullable();
            });
            $emailGroups = DB::table('email_groups')->select('email','group')->get();
            foreach($emailGroups as $emailGroup){
                $_pass_key = $emailGroup->email.$emailGroup->group;
                $pass_key = md5($_pass_key);
                DB::table('email_groups')->where('email',$emailGroup->email)->update(['pass_key'=>$pass_key]);
            }
            // db pass_key = md5(email + group)
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('email_groups', 'pass_key')){
            Schema::table('email_groups', function (Blueprint $table) {
                $table->dropColumn('pass_key');
            });
        }
    }
}
