<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModules5 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->integer('venue_if')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->dropColumn('venue_if');
        });
    }
}
