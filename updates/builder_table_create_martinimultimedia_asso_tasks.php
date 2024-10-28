<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoTasks extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            
            $table->dateTime('begin')->nullable();
            $table->dateTime('end')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_tasks');
    }
}
