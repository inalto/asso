<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoCourseEnrollments2 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_course_enrollments', function($table)
        {
            $table->renameColumn('training_id', 'module_id');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_course_enrollments', function($table)
        {
            $table->renameColumn('module_id', 'training_id');
        });
    }
}
