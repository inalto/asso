<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoTeachers extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_teachers', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_teachers');
    }
}
