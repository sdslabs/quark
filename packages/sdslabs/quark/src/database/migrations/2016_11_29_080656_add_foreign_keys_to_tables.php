<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('user_roles');
        });

        Schema::table('problems', function (Blueprint $table) {
            $table->foreign('solution_id')->references('id')->on('solutions');
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('uploader_id')->references('id')->on('users');
            $table->foreign('problem_type_id')->references('id')->on('problem_types');
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->foreign('practice_judge_id')->references('id')->on('judges');
            $table->foreign('competition_judge_id')->references('id')->on('judges');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('competition_id')->references('id')->on('competitions');
            $table->foreign('owner_id')->references('id')->on('users');
        });

        Schema::table('team_user_maps', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('team_id')->references('id')->on('teams');
        });

        Schema::table('practice_logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('problem_id')->references('id')->on('problems');
        });

        Schema::table('competition_logs', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('problem_id')->references('id')->on('problems');
        });

        Schema::table('user_team_invites', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('user_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::table('problems', function (Blueprint $table) {
            $table->dropForeign(['solution_id']);
            $table->dropForeign(['competition_id']);
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['uploader_id']);
            $table->dropForeign(['problem_type_id']);
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->dropForeign(['practice_judge_id']);
            $table->dropForeign(['competition_judge_id']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['competition_id']);
            $table->dropForeign(['owner_id']);
        });

        Schema::table('team_user_maps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['team_id']);
        });

        Schema::table('practice_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['problem_id']);
        });

        Schema::table('competition_logs', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['problem_id']);
        });

        Schema::table('user_team_invites', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
