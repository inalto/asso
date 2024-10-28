<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoTeacherTraining extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_teacher_training', function($table)
        {
            $table->integer('teacher_id')->unsigned();
            $table->integer('training_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_teacher_training');
    }
}
