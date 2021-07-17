<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            //Only Logged In Users Can Post a Comment.
            $table->foreignId('user_id')->constrained();

            //Instead Of commentable_id, type, BigInteger & String,...
            $table->morphs('commentable');

            $table->unsignedInteger('parent_id')->default(0);

            $table->boolean('approved')->default(0);
            $table->text('comment')->nullable();

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
        Schema::dropIfExists('comments');
    }
}
