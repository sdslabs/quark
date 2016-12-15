<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique()->comment('To be shown in url');
            $table->string('title', 100);
            $table->text('description');
            $table->integer('competition_id')->unsigned()->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('uploader_id')->unsigned();
            $table->boolean('practice')->default(0)->comment('If the problem is to be displayed in practice arena');
            $table->timestamps();
            $table->softDeletes();

            /* Foreign Keys */
            // $table->foreign('competition_id')->references('id')->on('competitions');
            // $table->foreign('creator_id')->references('id')->on('users');
            // $table->foreign('uploader_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problems');
    }
}
