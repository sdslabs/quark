<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('practice_judge_id')->unsigned()->nullable();
            $table->integer('competition_judge_id')->unsigned()->nullable();
            $table->float('practice_score')->unsigned()->nullable();
            $table->float('competition_score')->unsigned()->nullable();
            $table->string('solution', 200)->comment("Hash in case of string/integer solution, file path in case of files");

            /* Foreign Keys */
            // $table->foreign('practice_judge_id')->references('id')->on('judges');
            // $table->foreign('competition_judge_id')->references('id')->on('judges');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solutions');
    }
}
