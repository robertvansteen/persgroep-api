<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('category_story', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('story_id')->unsigned();
			$table->integer('category_id')->unsigned();
            $table->timestamps();

			$table->foreign('category_id')
				  ->references('id')->on('categories')
				  ->onDelete('cascade');

			$table->foreign('story_id')
				  ->references('id')->on('stories')
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
        Schema::drop('category_story');
    }
}
