<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('address_type_id')->index();
            $table->foreign('address_type_id')->references('id')->on('address_types');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address_first');
            $table->string('address_second')->nullable();
            $table->string('city');
            $table->unsignedBigInteger('state_id')->index();
            $table->foreign('state_id')->references('id')->on('states');
            $table->string('zip_code');
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
        Schema::dropIfExists('addresses');
    }
}
