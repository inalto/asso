<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTrainings5 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->integer('duration')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->dropColumn('duration');
        });
    }
}
