<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModules6 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->renameColumn('venue_if', 'venue_id');
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->renameColumn('venue_id', 'venue_if');
        });
    }
}
