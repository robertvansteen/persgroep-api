<?php

namespace App;

use Hash;
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
     * Mutate the password attribute when set.
     *
     * @param String password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
