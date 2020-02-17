<?php

use App\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        OrderStatus::truncate();
        DB::statement("SET foreign_key_checks=1");

        OrderStatus::create(['order_status' => 'Order Received']);
        OrderStatus::create(['order_status' => 'Payment Pending']);
        OrderStatus::create(['order_status' => 'Payment Authorized']);
        OrderStatus::create(['order_status' => 'Order Processing']);
        OrderStatus::create(['order_status' => 'Order Shipped']);
        OrderStatus::create(['order_status' => 'Order Canceled']);
        OrderStatus::create(['order_status' => 'Order Not Yet Received']);
    }
}
