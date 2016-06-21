<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
	/**
	 * Defines the users relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function users() {
		return $this->belongsToMany('App\User');
	}
}
