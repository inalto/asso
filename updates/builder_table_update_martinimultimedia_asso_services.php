<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoServices extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_services', function($table)
        {
            $table->string('color')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_services', function($table)
        {
            $table->dropColumn('color');
        });
    }
}
