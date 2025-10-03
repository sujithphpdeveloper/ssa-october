<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSmsSsaTournaments extends Migration
{
    public function up()
    {
        Schema::create('sms_ssa_tournaments', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('location')->nullable();
            $table->date('date');
            $table->time('time')->nullable();
            $table->boolean('is_published')->default(1);
            $table->integer('programme_id')->nullable()->unsigned();

            $table->foreign('programme_id')
              ->references('id')->on('sms_ssa_programs')
              ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_ssa_tournaments');
    }
}
