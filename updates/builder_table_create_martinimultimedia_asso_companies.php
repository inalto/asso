<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartinimultimediaAssoCompanies extends Migration
{
    public function up()
    {
        Schema::create('martinimultimedia_asso_companies', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('vat')->nullable();
            $table->string('cf')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->text('address')->nullable();
            $table->string('phones')->nullable();
            $table->string('emails')->nullable();
            $table->text('note')->nullable();
            $table->integer('ateco_id')->nullable();
            $table->string('umes')->nullable();
            $table->string('pec')->nullable();
            $table->string('sdi')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_companies');
    }
}
