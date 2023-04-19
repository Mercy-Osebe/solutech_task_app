<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $guarded = [];
    public static $statuses = array(
        'OPEN' => 'OPEN',
        'IN_PROGRESS' => 'IN PROGRESS',
        'COMPLETED' => 'COMPLETED',
        'ON_HOLD' => 'ON HOLD',
        'CANCELLED' => 'CANCELLED',
    );

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
