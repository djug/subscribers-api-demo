<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('value')->nullable();
            /* we can of course link the field to just its subscriber and get the user from it, but since this is just a demo application adding a field for the user will make our lives a little bit easier*/
            $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('subscriber_id')->unsigned();
                $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
            $table->integer('accepted_field_id')->unsigned();
                $table->foreign('accepted_field_id')->references('id')->on('accepted_fields')->onDelete('cascade');

            $table->timestamps();
            $table->unique(['user_id', 'subscriber_id', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
