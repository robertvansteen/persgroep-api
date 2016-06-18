<?php

namespace App\Console\Commands;

use App\Story;
use Illuminate\Console\Command;

class CalculateScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the score of the stories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

		$this->comment('Fetching stories with score');
		$stories = Story::rawScore()->get();
		$this->comment('All done!');
		$bar = $this->output->createProgressBar($stories->count());
		$this->comment('Writing score to the database');

		foreach ($stories as $story) {
			$story->score = $story->raw_score;
			$story->save();
    		$bar->advance();
		}
    }
}
