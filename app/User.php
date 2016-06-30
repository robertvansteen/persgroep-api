<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Define the stories relationship.
     *
     * @return \Illuminate\Database\Eloquent\HasMany
     */
    public function stories() {
        return $this->hasMany('App\Story');
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
	 * Defines the assignments relationship.
	 *
	 * @return Illuminate\Database\Eloquent\BelongsToMany
	 */
	public function assignments() {
		return $this->belongsToMany('App\Assignment');
	}
}
