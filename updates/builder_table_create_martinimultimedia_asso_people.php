<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoPeople extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_people', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_city')->nullable();
            $table->string('birth_state')->nullable();

            $table->string('cf')->nullable();
            $table->text('phones')->nullable();
            $table->text('emails')->nullable();
            $table->text('note')->nullable();
            $table->string('task')->nullable();
            $table->integer('company_id')->nullable();
            $table->boolean('cna')->nullable();
            $table->string('other')->nullable();

        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_people');
    }
}
