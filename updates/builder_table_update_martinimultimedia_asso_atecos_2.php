<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoAtecos2 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_atecos', function($table)
        {
            $table->integer('risk')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_atecos', function($table)
        {
            $table->integer('risk')->default(null)->change();
        });
    }
}
