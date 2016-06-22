<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('assignment_user', function (Blueprint $table) {
			$table->integer('assignment_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->index();
			$table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

			$table->foreign('assignment_id')
				  ->references('id')->on('assignments')
				  ->onDelete('cascade');

			$table->foreign('user_id')
				  ->references('id')->on('users')
				  ->onDelete('cascade');

			$table->primary(['assignment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('assignment_user');
    }
}
