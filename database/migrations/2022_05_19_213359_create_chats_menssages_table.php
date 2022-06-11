<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsMenssagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats_menssages', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_send_id')->unsigned();
            $table->foreign('user_send_id')->references('id')->on('users');


            $table->bigInteger('user_receive_id')->unsigned();;
            $table->foreign('user_receive_id')->references('id')->on('users'); 

            $table->bigInteger('status_id')->unsigned();;
            $table->foreign('status_id')->references('id')->on('chats_menssages_status');

            $table->text('contens');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats_menssages');
    }
}
