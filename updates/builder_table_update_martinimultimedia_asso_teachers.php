<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTeachers extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_teachers', function($table)
        {
            $table->integer('training_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_teachers', function($table)
        {
            $table->dropColumn('training_id');
        });
    }
}
