<?php

use App\Story;
use Illuminate\Database\Seeder;

class StoriesTableSeeder extends Seeder
{
	/**
	 * The amount of categories in the database.
	 *
	 * @type Number
	 */
	protected $categoriesCount = 0;

	/**
	 * Set a random category on the story.
	 *
	 * @param Story $story
	 */
	protected function setCategory(Story $story)
	{
		$categoryId = mt_rand(1, $this->categoriesCount);
		$story->categories()->attach($categoryId);
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stories')->truncate();

        $this->command->info('Seeding a random amount of stories for every user...');

		$this->categoriesCount = App\Category::count();

        App\User::all()->each(function($user) {
            $amount = mt_rand(-20, 10);

            if ($amount < 0) return;

            $factory = factory(App\Story::class, $amount)->create([
                'user_id' => $user->id,
            ]);

			if ($factory instanceof App\Story) {
				$this->setCategory($factory);
			}

			if ($factory instanceof Illuminate\Database\Eloquent\Collection) {
				$factory->each(function ($story) {
					$this->setCategory($story);
				});
			}
        });
    }
}
