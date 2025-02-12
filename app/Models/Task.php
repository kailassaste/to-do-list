<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = ['title', 'description', 'startDate', 'dueDate', 'status_id', 'createdBy'];

    public function status() 
    {
        return $this->belongsTo(MstStatus::class, 'status_id');
    }
}
