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
            $table->string('name');
            $table->string('barcode')->nullable()->unique();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('product_types')->onDelete('set null');
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
