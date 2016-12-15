<?php

namespace SDSLabs\Quark\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('uploader_id')->references('id')->on('users');
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users');
        });

        Schema::table('user_team_maps', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });

        Schema::table('practice_submissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('problem_id')->references('id')->on('problems')->onDelete('cascade');
        });

        Schema::table('competition_submissions', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('problem_id')->references('id')->on('problems');
        });

        Schema::table('user_team_invites', function (Blueprint $table) {
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
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
        Schema::table('problems', function (Blueprint $table) {
            $table->dropForeign(['competition_id']);
            $table->dropForeign(['creator_id']);
            $table->dropForeign(['uploader_id']);
        });

        Schema::table('solutions', function (Blueprint $table) {
            $table->dropForeign(['problem_id']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['competition_id']);
            $table->dropForeign(['owner_id']);
        });

        Schema::table('user_team_maps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['team_id']);
        });

        Schema::table('practice_submissions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['problem_id']);
        });

        Schema::table('competition_submissions', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['problem_id']);
        });

        Schema::table('user_team_invites', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
