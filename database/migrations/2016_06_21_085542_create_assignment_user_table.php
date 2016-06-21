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
            $table->increments('id');
			$table->integer('assignment_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();

			$table->foreign('assignment_id')
				  ->references('id')->on('assignments')
				  ->onDelete('cascade');

			$table->foreign('user_id')
				  ->references('id')->on('users')
				  ->onDelete('cascade');
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
