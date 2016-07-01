<?php

namespace App\Events;

use App\Like;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StoryLiked extends Event implements ShouldBroadcast
{
    use SerializesModels;

	/**
	 * The like of the event.
	 *
	 * @var Like
	 */
	public $like;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Like $like)
    {
		$like->load('story');
		$this->like = $like;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['events'];
    }

	/**
	 * Get the broadcast event name.
	 *
	 * @return string
	 */
	public function broadcastAs()
	{
	    return 'story.liked';
	}
}
