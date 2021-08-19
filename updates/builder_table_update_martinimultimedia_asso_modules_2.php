<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModules2 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->integer('credits')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->dropColumn('credits');
        });
    }
}
