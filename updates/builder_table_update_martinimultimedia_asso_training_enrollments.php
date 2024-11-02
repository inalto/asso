<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTrainingEnrollments extends Migration
{
    public function up()
    {
        Schema::rename('martinimultimedia_asso_module_enrollments', 'martinimultimedia_asso_training_enrollments');
    }
    
    public function down()
    {
        Schema::rename('martinimultimedia_asso_training_enrollments', 'martinimultimedia_asso_module_enrollments');
    }
}
