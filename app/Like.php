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
}
