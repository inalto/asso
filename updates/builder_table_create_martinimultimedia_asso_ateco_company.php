<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoAtecoCompany extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_ateco_company', function($table)
        {
            $table->integer('company_id');
            $table->integer('ateco_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_ateco_company');
    }
}
