<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateSmsSsaTestimonials extends Migration
{
    public function up()
    {
        Schema::table('sms_ssa_testimonials', function($table)
        {
            $table->boolean('is_published')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('sms_ssa_testimonials', function($table)
        {
            $table->dropColumn('is_published');
        });
    }
}
