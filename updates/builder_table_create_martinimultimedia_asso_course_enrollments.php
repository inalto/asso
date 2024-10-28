<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoCourseEnrollments extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_course_enrollments', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('person_id')->nullable();
            $table->dateTime('enrollment_date')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_course_enrollments');
    }
}
