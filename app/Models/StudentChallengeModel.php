<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentChallengeModel extends Model
{
    use HasFactory;

    protected $table = 'student_challenge';

    protected $fillable = [
        'student_id',
        'challenge_id',
        'completed',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function challenge()
    {
        return $this->belongsTo(ChallengeModel::class, 'challenge_id');
    }
}
