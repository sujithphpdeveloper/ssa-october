<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSmsSsaPrograms extends Migration
{
    public function up()
    {
        Schema::create('sms_ssa_programs', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->text('excerpt')->nullable();
            $table->string('slug')->unique();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_ssa_programs');
    }
}
