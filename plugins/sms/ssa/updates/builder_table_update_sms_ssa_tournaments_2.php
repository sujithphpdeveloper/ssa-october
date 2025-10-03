<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaTournaments2 extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_tournaments', function($table)
        {
            $table->dateTime('date')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
            $table->dropColumn('time');
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_tournaments', function($table)
        {
            $table->date('date')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
            $table->time('time')->nullable();
        });
    }
}
