<?php

use App\PaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        PaymentGateway::truncate();
        DB::statement("SET foreign_key_checks=1");

        PaymentGateway::create(['name' => 'Authorize.net']);
        PaymentGateway::create(['name' => 'PayPal']);
    }
}
