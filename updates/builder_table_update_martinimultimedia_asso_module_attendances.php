<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModuleAttendances extends Migration
{
    public function up()
    {
        Schema::rename('martinimultimedia_asso_attendance', 'martinimultimedia_asso_module_attendances');
    }
    
    public function down()
    {
        Schema::rename('martinimultimedia_asso_module_attendances', 'martinimultimedia_asso_attendance');
    }
}
