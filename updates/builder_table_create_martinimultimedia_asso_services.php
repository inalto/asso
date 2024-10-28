<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoServices extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_services', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_services');
    }
}
