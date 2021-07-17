<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('percent');
            // $table->string('user')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });

        Schema::create('discount_product', function (Blueprint $table) {
            $table->foreignId('discount_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('product_id')->constrained()->onDelete('CASCADE');
            $table->primary(['discount_id', 'product_id']);
        });

        Schema::create('category_discount', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('discount_id')->constrained()->onDelete('CASCADE');
            $table->primary(['category_id', 'discount_id']);
        });

        //Added Later
        Schema::create('discount_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('discount_id')->constrained()->onDelete('CASCADE');
            $table->primary(['discount_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_product');
        Schema::dropIfExists('category_discount');
        Schema::dropIfExists('discount_user');
        Schema::dropIfExists('discounts');
    }
}
