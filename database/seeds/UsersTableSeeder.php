<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Amount of users to generate.
     *
     * @type Number
     */
     protected $amount = 200;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

		factory(App\User::class)->create([
			'name'      => 'John Doe',
			'email'     => 'johndoe@persgroep.nl',
			'password'  => Hash::make('test123'),
			'image_url' => 'http://en.gravatar.com/userimage/99097878/3438bd82819ee3f6fa7847e4c78dac2c.jpg?size=200',
		]);

		factory(App\User::class)->create([
			'name'      => 'Jane Doe',
			'email'     => 'janedoe@persgroep.nl',
			'password'  => Hash::make('test123'),
			'image_url' => 'https://s3.amazonaws.com/uifaces/faces/twitter/esthercrawford/128.jpg',
		]);

        $this->command->info('Seeding ' . $this->amount . ' users...');

		$response = file_get_contents('http://api.randomuser.me/?results=' . $this->amount);
		$data = json_decode($response)->results;

        factory(App\User::class, $this->amount)->create()->each(function ($user, $index) use ($data) {
			$person = $data[$index];
			$user->name = ucwords($person->name->first . ' ' . $person->name->last);
			$user->image_url = $person->picture->large;
			$user->save();
		});
    }
}
