<?php

namespace App;

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
}
