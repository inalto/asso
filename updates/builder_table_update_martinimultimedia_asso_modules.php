<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaAssoModules extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->string('name', 255)->nullable()->change();
            $table->string('slug', 255)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('teacher', 255)->nullable()->change();
            $table->integer('hours')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_asso_modules', function($table)
        {
            $table->string('name', 255)->nullable(false)->change();
            $table->string('slug', 255)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->string('teacher', 255)->nullable(false)->change();
            $table->integer('hours')->nullable(false)->change();
        });
    }
}
