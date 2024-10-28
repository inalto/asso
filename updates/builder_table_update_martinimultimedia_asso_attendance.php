<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoAttendance extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_attendance', function($table)
        {
            $table->integer('person_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_attendance', function($table)
        {
            $table->dropColumn('person_id');
        });
    }
}
