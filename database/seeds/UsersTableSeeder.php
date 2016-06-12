<?php

global $guser;

$guser = null;

use Illuminate\Database\Seeder;
use Linku\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 50)->create()->each(function(User $user) {
            global $guser;

            $guser = $user;

            $times = random_int(15, 30);

            for($i = 0; $i < $times; $i++) {
                $user->folders()->save(factory(\Linku\Models\Folder::class)->make());
            }

        });

        factory(\Linku\Models\Share::class, 15)->create();
    }
}
