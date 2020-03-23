<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'title', 'message', 'image', 'user_id', 'status_id'
	];

    /**
     * Get the user that owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

	public function status()
	{
		return $this->belongsTo(Status::class);
	}
}
