<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTeamInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_team_invites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('status')->comment("1 => team to user, 2 => user to team, 0 => accepted");
            $table->string('token', 32)->unique();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);

            /* Foreign Keys */
            // $table->foreign('team_id')->references('id')->on('teams');
            // $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_team_invites');
    }
}
