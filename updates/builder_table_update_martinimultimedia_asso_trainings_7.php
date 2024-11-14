<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTrainings7 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->renameColumn('date', 'start_date');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->renameColumn('start_date', 'date');
        });
    }
}
