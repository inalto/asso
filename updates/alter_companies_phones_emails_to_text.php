<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AlterCompaniesPhoneEmailsToText extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_asso_companies', function($table)
        {
            $table->text('phones')->nullable()->change();
            $table->text('emails')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('martinimultimedia_asso_companies', function($table)
        {
            $table->string('phones')->nullable()->change();
            $table->string('emails')->nullable()->change();
        });
    }
}