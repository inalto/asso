<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTrainings extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->dropColumn('time_begin');
            $table->dropColumn('time_end');
            $table->dropColumn('repeat_event');
            $table->dropColumn('repeat_mode');
            $table->dropColumn('end_repeat_on');
            $table->dropColumn('location_street');
            $table->dropColumn('location_number');
            $table->dropColumn('location_zip');
            $table->dropColumn('location_city');
            $table->dropColumn('teacher');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->dateTime('time_begin');
            $table->dateTime('time_end');
            $table->boolean('repeat_event')->default(0);
            $table->integer('repeat_mode')->unsigned()->default(0);
            $table->dateTime('end_repeat_on')->nullable();
            $table->string('location_street', 255)->nullable();
            $table->string('location_number', 255)->nullable();
            $table->string('location_zip', 255)->nullable();
            $table->string('location_city', 255)->nullable();
            $table->string('teacher', 255)->nullable();
        });
    }
}
