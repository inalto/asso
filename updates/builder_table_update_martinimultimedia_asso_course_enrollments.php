<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoCourseEnrollments extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_course_enrollments', function($table)
        {
            $table->renameColumn('course_id', 'training_id');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_course_enrollments', function($table)
        {
            $table->renameColumn('training_id', 'course_id');
        });
    }
}
