<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('problem_tags', function (Blueprint $table) {
			$table->integer('problem_id')->unsigned();;
			$table->integer('tag_id')->unsigned();;
			$table->unique(['problem_id', 'tag_id']);
			$table->timestamps();
		});
		Schema::table('problem_tags', function (Blueprint $table) {
			$table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('problem_tags', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['problem_id']);
        });
		Schema::dropIfExists('problem_tags');
    }
}

