<?php

namespace App;

use DB;
use Auth;

class Story extends Model
{
	/**
	 * Specify the fillable properties.
	 *
	 * @type {Array}
	 */
	protected $fillable = [
		'title',
		'excerpt',
		'body',
	];

	/**
	 * Specify the hidden properties.
	 *
	 * @type {Array}
	 */
	protected $hidden = [
		'pivot',
		'likes',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id'          => 'string',
		'user_id'     => 'string',
		'score'       => 'float',
	];

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'liked',
		'like_count',
	];

	/**
	 * The relationships to eager load the model with.
	 *
	 * @var array
	 */
	protected $with = [
		'likes',
		'author',
		'categories',
	];

	/**
	 * Define the user (author) relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\BelongsTo
	 */
	public function author() {
		return $this->belongsTo('App\User', 'user_id');
	}

	/**
	 * Define the likes relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\HasMany
	 */
	public function likes() {
		return $this->hasMany('App\Like');
	}

	/**
	 * Defines the categories relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function categories() {
		return $this->belongsToMany('App\Category');
	}

	/**
	 * Scope the query to show the most popular stories.
	 *
	 * @param  Illuminate\Database\Query $query
	 * @return Illuminate\Database\Query
	 */
	public function scopeRawScore($query) {
		return $query
			->select([
				'stories.*',
				DB::raw(
				'COUNT(DISTINCT(story_likes.id)) + (COUNT(user_stories_likes.story_id) * 0.2) AS raw_score'
				)
			])
			->leftJoin('likes AS story_likes', 'stories.id', '=', 'story_likes.story_id')
			->leftJoin('users', 'story_likes.user_id', '=', 'users.id')
			->leftJoin('stories AS user_stories', 'users.id', '=', 'user_stories.user_id')
			->leftJoin('likes AS user_stories_likes', 'user_stories.id', '=', 'user_stories_likes.story_id')
			->groupBy('stories.id');
	}

	/**
	 * Scope by sorting by popularity.
	 *
	 * @param  Query $query [description]
	 * @return Query
	 */
	public function scopePopular($query) {
		return $query->orderBy('score', 'DESC');
	}

	/**
	 * Scope the query by fetching related stories.
	 *
	 * @param  Query $query
	 * @param  Story $story
	 * @return Query
	 */
	public function scopeRelated($query, Story $story) {
		return $query->whereHas('categories', function ($query) use ($story) {
			$query->whereIn('category_id', $story->categories->pluck('id'))
				  ->where('story_id', '!=', $story->id);
		});
	}

	/**
	 * Accessor for the liked attribute.
	 *
	 * @return Boolean
	 */
	public function getLikedAttribute() {
		$user = app('Dingo\Api\Auth\Auth')->user();

		if (!$user) return false;

		$result = $this->likes->search(function ($value, $key) use ($user) {
			return $value->user_id === $user->id;
		});

		return is_int($result);
	}

	/**
	 * Accessor for the like count attribute.
	 *
	 * @return Boolean
	 */
	public function getLikeCountAttribute() {
		return count($this->likes);
	}
}
