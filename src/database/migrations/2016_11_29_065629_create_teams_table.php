<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('score')->default(0);
            $table->timestamp('score_updated_at')->nullable();
            $table->integer('competition_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->timestamps();

            $table->unique(['competition_id', 'name']);

            /* Foreign Keys */
            // $table->foreign('competition_id')->references('id')->on('competitions');
            // $table->foreign('owner_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
