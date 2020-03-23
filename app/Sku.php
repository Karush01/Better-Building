<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description', 'regression', 'treatmentfactor', 'instruction', 'сyclic_days', 'free_text'
    ];
    
    /**
     * Get the tasks for the current sku post.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
