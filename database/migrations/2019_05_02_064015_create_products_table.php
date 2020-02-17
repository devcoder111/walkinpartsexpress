<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('api_web_category_id')->index();
            $table->unsignedBigInteger('web_category_id');
            $table->foreign('web_category_id')->references('id')->on('web_categories');
            $table->boolean('deleted')->default('0');
            $table->unsignedBigInteger('master_part_number')->unique();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->decimal('weight')->index();
            $table->decimal('price')->index();
            $table->text('prop_65_warning')->nullable();
            $table->text('specifications')->nullable();
            $table->unsignedInteger('quantity')->default(0);
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
