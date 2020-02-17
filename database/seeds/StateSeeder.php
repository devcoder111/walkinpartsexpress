<?php

use App\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = array_map('str_getcsv', file('./database/seeds/us-state-list.csv'));

        foreach ($states as $state) {
            State::create(['name' => $state[0], 'abbreviation' => $state[1]]);
        }


    }
}
