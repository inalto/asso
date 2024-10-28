<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoComuni extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_comuni', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('cod_reg')->nullable();
            $table->string('cod_ut')->nullable();
            $table->string('name')->nullable();
            $table->string('state')->nullable();
            $table->string('prov')->nullable();
            $table->string('cod_state')->nullable();
            $table->string('cod_cat')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_comuni');
    }
}
