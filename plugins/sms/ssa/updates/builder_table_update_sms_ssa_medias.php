<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaMedias extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_medias', function($table)
        {
            $table->boolean('is_published')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_medias', function($table)
        {
            $table->dropColumn('is_published');
        });
    }
}
