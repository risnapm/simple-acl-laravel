<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogError extends Model
{
    protected $table = 'log_errors';
    protected $primaryKey = 'id';
    public $timestamps = false;

}
