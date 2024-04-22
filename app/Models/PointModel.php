<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointModel extends Model
{
    use HasFactory;

    protected $table = 'point';
    protected $fillable = ['student_id', 'points'];


    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


}
