<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaPrograms2 extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_programs', function($table)
        {
            $table->text('data')->nullable();
            $table->text('small_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_programs', function($table)
        {
            $table->dropColumn('data');
            $table->dropColumn('small_description');
        });
    }
}
