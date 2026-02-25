<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSmsSsaTestimonials extends Migration
{
    public function up()
    {
        Schema::create('sms_ssa_testimonials', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('name');
            $table->string('location');
            $table->text('message');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sms_ssa_testimonials');
    }
}
