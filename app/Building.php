<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
        'city',
        'address',
        'image',
        'description',
        'management_company_id',
        'tenants_count',
        'floors',
        'parking_levels',
        'entrepreneur',
        'constructor',
        'committee_members',
        'building_description'
	];

    public function managementCompany()
    {
        return $this->belongsTo(ManagementCompany::class);
	}
	
	/**
     * Get the tasks for the current building.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

	/**
	 * Get the user for the current building.
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
