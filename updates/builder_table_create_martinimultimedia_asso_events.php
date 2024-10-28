<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoEvents extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('training_id')->unsigned();
            $table->string('subject')->nullable();
            $table->string('location')->nullable();
            $table->boolean('all_day')->default(false);
            $table->boolean('reminder')->default(false);
            $table->integer('priority')->unsigned();
            $table->integer('recurrence')->unsigned()->default(0);
            $table->string('recurrence_rule')->nullable();
            $table->integer('every')->unsigned();

            $table->timestamp('recurrence_start_date')->nullable();
            $table->timestamp('recurrence_end_date')->nullable();

            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_events');
    }
}
