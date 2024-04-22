<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeModel extends Model
{
    use HasFactory;

    protected $table = 'challenge';
    protected $fillable = [
        'name', 'description', 'challenge_type', 'grade_count', 'minimum_grade',
        'subject_id', 'start_date', 'end_date', 'reward_points'
    ];


    public function subject()
    {
        return $this->belongsTo(SubjectModel::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_challenge', 'challenge_id', 'student_id')->withPivot('completed');
    }

    public function studentChallenges()
    {
        return $this->hasMany(StudentChallengeModel::class, 'challenge_id');
    }

}
