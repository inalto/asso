<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoAttendance extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_attendance', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('module_id')->nullable();
            $table->dateTime('attended_date')->nullable();
            $table->boolean('status')->nullable()->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_attendance');
    }
}
