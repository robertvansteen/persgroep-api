<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Amount of users to generate.
     *
     * @type Number
     */

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        $this->command->info('Seeding ' . $this->amount . ' users...');

        factory(App\User::class)->create([
            'email'    => 'studio@rovansteen.nl',
            'password' => Hash::make('test123'),
        ]);

        factory(App\User::class, $this->amount)->create();
    }
}
