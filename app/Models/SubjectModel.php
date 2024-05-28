<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'subject';
    protected $fillable = ['name', 'status', 'grading_type'];


    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subject', 'subject_id', 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')->withPivot('class_id')->withTimestamps();;
    }

    public function grades()
    {
        return $this->hasMany(GradeModel::class, 'subject_id');
    }

    public function challenges()
    {
        return $this->hasMany(ChallengeModel::class);
    }

    public function homework()
    {
        return $this->hasMany(HomeworkModel::class, 'subject_id');
    }

}
