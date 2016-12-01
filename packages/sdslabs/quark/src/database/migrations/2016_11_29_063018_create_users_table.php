<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unique();
            $table->string('provider', 100);
            $table->binary('credentials');
            $table->string('username', 250)->unique();
            $table->string('fullname', 256);
            $table->string('email', 250)->unique();
            $table->string('image', 256);
            $table->float('score')->default(0)->comment('Score in practice arena.');
            $table->timestamps();

            /* Foreign Keys */
            // $table->foreign('role_id')->references('id')->on('user_roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
