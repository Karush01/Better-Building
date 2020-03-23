<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagementCompany extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'city', 'address', 'description','phone'
	];

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

	/**
	 * Get the user that owns the company.
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
