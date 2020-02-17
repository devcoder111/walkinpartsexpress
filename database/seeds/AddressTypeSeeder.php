<?php

use App\AddressType;
use Illuminate\Database\Seeder;

class AddressTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AddressType::create(['type' => 'Billing']);
        AddressType::create(['type' => 'Shipping']);
    }
}
