<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaMedias2 extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_medias', function($table)
        {
            $table->string('video_url', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_medias', function($table)
        {
            $table->string('video_url', 255)->nullable(false)->change();
        });
    }
}
