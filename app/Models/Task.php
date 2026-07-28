<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'priority', 'status', 'description', 'created_by'];
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
