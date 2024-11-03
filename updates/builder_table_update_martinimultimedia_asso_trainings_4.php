<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoTrainings4 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->integer('venue_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_trainings', function($table)
        {
            $table->dropColumn('venue_id');
        });
    }
}
