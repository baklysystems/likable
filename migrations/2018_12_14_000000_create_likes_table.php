<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('like.likes_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(config('like.user_foreign_key'))->index()->comment('Reference Liker Model');
            $table->morphs('likeable');
            $table->enum('like', ['like','dislike'])->default('like');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('like.likes_table'));
    }
}
