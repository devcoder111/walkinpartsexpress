<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('admin')->to('access-admin');
        Bouncer::allow('admin')->to('access-site');
        Bouncer::allow('customer')->to('access-site');

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'mikem@millerdavisagency.com',
            'password' => bcrypt('al24u85jcxka'),
        ]);

        $user = User::where('email', 'mikem@millerdavisagency.com')->first();

        Bouncer::assign('admin')->to($user);
    }
}
