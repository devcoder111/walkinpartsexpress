<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTaxCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_tax_caches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('fromZip')->unsigned()->index();
            $table->integer('toZip')->unsigned()->index();
            $table->string('fromState', 2)->index();
            $table->string('toState', 2)->index();
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
        Schema::dropIfExists('sales_tax_caches');
    }
}
