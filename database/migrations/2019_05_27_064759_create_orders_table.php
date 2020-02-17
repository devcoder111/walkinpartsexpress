<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('subtotal_cost');
            $table->decimal('shipping_cost');
            $table->decimal('tax_cost');
            $table->decimal('total_cost');
            $table->unsignedBigInteger('api_order_id')->index();
            $table->unsignedBigInteger('order_status_id')->index();
            $table->foreign('order_status_id')->references('id')->on('order_statuses');
            $table->string('email_address')->nullable();
            $table->string('last_4_cc_number')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->index();
            $table->foreign('shipping_address_id')->references('id')->on('addresses');
            $table->unsignedBigInteger('billing_address_id')->index();
            $table->foreign('billing_address_id')->references('id')->on('addresses');
            $table->string('authorize_net_transaction_id')->nullable();
            $table->string('paypal_transaction_id')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
