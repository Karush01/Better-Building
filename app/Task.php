<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_id', 'sku_id', 'status_id', 'image', 'сyclic_days', 'note', 'last_date'
    ];
    /**
     * Get the sku that owns the task.
     */
    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }

    /**
     * Get the stauts that owns the task.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the stauts that owns the task.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
