<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBreakLog extends Model
{
    protected $table = 'user_break_logs';
    protected $fillable = ['user_id','break_start_time','break_end_time','updated_at','created_at',];
}
