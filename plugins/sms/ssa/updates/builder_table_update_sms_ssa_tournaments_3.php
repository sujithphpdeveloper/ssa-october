<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaTournaments3 extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_tournaments', function($table)
        {
            $table->string('url')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_tournaments', function($table)
        {
            $table->dropColumn('url');
        });
    }
}
