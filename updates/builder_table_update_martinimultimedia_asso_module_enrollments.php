<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModuleEnrollments extends Migration
{
    public function up()
    {
        Schema::rename('martinimultimedia_asso_course_enrollments', 'martinimultimedia_asso_module_enrollments');
    }
    
    public function down()
    {
        Schema::rename('martinimultimedia_asso_module_enrollments', 'martinimultimedia_asso_course_enrollments');
    }
}
