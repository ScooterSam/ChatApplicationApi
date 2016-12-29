<?php

use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\User::class, 4)->create();
        factory(App\Models\Chat::class, 50)->create();
        factory(App\Models\Participant::class, 100)->create();
        factory(App\Models\Message::class, 300)->create();
    }
}
