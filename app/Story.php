<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
	/**
	 * Specify the fillable properties.
	 *
	 * @type {Array}
	 */
	protected $fillable = [
		'title',
		'body',
	];

	/**
	 * Specify the hidden properties.
	 *
	 * @type {Array}
	 */
	protected $hidden = [
		'score',
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
	 * Scope the query to show the most popular stories.
	 *
	 * @param  Illuminate\Database\Query $query
	 * @return Illuminate\Database\Query
	 */
	public function scopeScore($query) {
		return $query
			->select([
				'stories.*',
				DB::raw(
				'COUNT(DISTINCT(likes.id)) + (COUNT(user_stories_likes.story_id) * 0.2) AS score'
				)
			])
			->leftJoin('likes', 'stories.id', '=', 'likes.story_id')
			->leftJoin('users', 'likes.user_id', '=', 'users.id')
			->leftJoin('stories AS user_stories', 'users.id', '=', 'user_stories.user_id')
			->leftJoin('likes AS user_stories_likes', 'user_stories.id', '=', 'user_stories_likes.story_id')
			->groupBy('stories.id');
	}
}
