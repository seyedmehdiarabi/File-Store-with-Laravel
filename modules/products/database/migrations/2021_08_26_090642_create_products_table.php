<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('text')->nullable();
            $table->integer('view')->default(0);
            $table->tinyInteger('status');
            $table->integer('price');
            $table->integer('time');
            $table->integer('order_number')->default(0);
            $table->integer('category_id');
            $table->text('links')->nullable();
            $table->text('tag')->nullable();
            $table->string('pic')->nullable();
            $table->string('download_times')->nullable();
            $table->integer('download_number')->nullable();
            $table->string('download_size')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
