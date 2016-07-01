<?php

namespace App;

class Like extends Model
{
	/**
	 * Specify the fillable properties.
	 *
	 * @type {Array}
	 */
	protected $fillable = [
		'user_id',
		'story_id',
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id'       => 'string',
		'story_id' => 'string',
		'user_id'  => 'string',
	];

	protected $with = ['user', 'story'];

	/**
	 * Define the user relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\BelongsTo
	 */
	public function user() {
		return $this->belongsTo('App\User');
	}

	/**
	 * Define the user relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\BelongsTo
	 */
	public function story() {
		return $this->belongsTo('App\Story');
	}
}
