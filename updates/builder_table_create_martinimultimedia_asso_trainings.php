<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoTrainings extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_trainings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('time_begin');
            $table->dateTime('time_end');
            // Should the event repeat?
            $table->boolean('repeat_event')->nullable(false)->unsigned(false)->default(0);
            $table->integer('repeat_mode')->nullable(false)->unsigned()->default(0);
            $table->dateTime('end_repeat_on')->nullable();

            $table->string('location_street', 255)->nullable();
            $table->string('location_number', 255)->nullable();
            $table->string('location_zip', 255)->nullable();
            $table->string('location_city', 255)->nullable();


            $table->string('teacher', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('hours', 255)->nullable();


            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_trainings');
    }
}
