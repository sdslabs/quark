<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->integer('problem_id')->unsigned();
            $table->string('status', 30);
            $table->string('submission', 200);
            $table->timestamps();

            /* Foreign Keys */
            // $table->foreign('team_id')->references('id')->on('teams');
            // $table->foreign('problem_id')->references('id')->on('problems');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_submissions');
    }
}
