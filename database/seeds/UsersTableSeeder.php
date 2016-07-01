<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Amount of users to generate.
     *
     * @type Number
     */
     protected $amount = 50;

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
            'email'     => 'studio@rovansteen.nl',
            'password'  => Hash::make('test123'),
			'image_url' => 'http://en.gravatar.com/userimage/99097878/3438bd82819ee3f6fa7847e4c78dac2c.jpg?size=200',
        ]);

		$response = file_get_contents('http://api.randomuser.me/?results=' . $this->amount);
		$data = json_decode($response)->results;

        factory(App\User::class, $this->amount)->create()->each(function ($user) use ($data) {
			$person = $data[$user->id - 2];
			$user->name = ucwords($person->name->first . ' ' . $person->name->last);
			$user->image_url = $person->picture->large;
			$user->save();
		});
    }
}
