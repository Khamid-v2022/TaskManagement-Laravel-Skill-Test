<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Task extends Model
{
    protected $fillable = ['project_id', 'name', 'priority', 'status', 'description'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
