<?php

use App\SocialMedia;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialMedia::create(['social_name' => 'facebook', 'target_url' => 'http://facebook.com/walkinpartsexpress']);
        SocialMedia::create(['social_name' => 'twitter', 'target_url' => 'https://twitter.com/walkinpartsexp']);
        SocialMedia::create(['social_name' => 'youtube', 'target_url' => 'https://www.youtube.com/channel/UCiMraX0SIAr3qIZC4P0QC8A/featured?view_as=subscriber']);
    }
}
