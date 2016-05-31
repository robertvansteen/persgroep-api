<?php

namespace App\Console\Commands;

use Feeds;
use App\Story;
use Illuminate\Console\Command;

class FetchRSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:rss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch a RSS feed';

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
        $feed = Feeds::make('feed://www.vice.com/rss');
        foreach ($feed->get_items() as $item) {
            $this->addStory($item);
        }
    }

    protected function addStory($item)
    {
        $id = $item->get_id(true);
        if (Story::where('atom_id', '=', $id)->exists()) {
            return false;
        }

        $story = new Story();
        $story->atom_id = $id;
        $story->user_id = 1;
        $story->title = $item->get_title();
        $story->body = $item->get_content();
        $story->image_url = $item->get_enclosure()->get_link();

        $story->save();
    }
}
