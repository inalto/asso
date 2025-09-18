<?php namespace MartiniMultimedia\Asso\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class RecreateTasksTable extends Migration
{
    public function up()
    {
        // Drop the existing table if it exists with wrong structure
        Schema::dropIfExists('martinimultimedia_asso_tasks');
        
        // Create the correct tasks table
        Schema::create('martinimultimedia_asso_tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->datetime('due_date')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->integer('created_by_user_id')->unsigned();
            $table->integer('assigned_to_user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['assigned_to_user_id', 'status']);
            $table->index(['due_date', 'status']);
            $table->index('priority');
        });
    }

    public function down()
    {
        Schema::dropIfExists('martinimultimedia_asso_tasks');
    }
}