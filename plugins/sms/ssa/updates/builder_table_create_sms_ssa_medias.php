<?php namespace SMS\SSA\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateSmsSsaMedias extends Migration
{
    public function up()
    {
        Schema::create('sms_ssa_medias', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('type');
            $table->string('video_url');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_ssa_medias');
    }
}
