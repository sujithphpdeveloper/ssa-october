<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaFormSubmissions extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_form_submissions', function($table)
        {
            $table->string('type')->nullable()->default('contact');
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_form_submissions', function($table)
        {
            $table->dropColumn('type');
        });
    }
}
