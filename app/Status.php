<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description'
    ];
    
    /**
     * Get the tasks for the current status.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

	public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}
}
