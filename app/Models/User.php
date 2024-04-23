<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'address',
        'date_of_birth',
        'gender',
        'student_class_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function studentClassId()
    {
        return $this->belongsTo(ClassModel::class, 'student_class_id');
    }

    public function teacherClasses()
    {
        return $this->belongsToMany(ClassModel::class, 'class_teacher', 'teacher_id', 'class_id');
    }

    public function teacherSubjects()
    {
        return $this->belongsToMany(SubjectModel::class, 'teacher_subject', 'teacher_id', 'subject_id')->withPivot('class_id')->withTimestamps();;
    }

    public function studentGrades()
    {
        return $this->hasMany(GradeModel::class, 'student_id');
    }

    public function teacherGrades()
    {
        return $this->hasMany(GradeModel::class, 'teacher_id');
    }

    public function challenges()
    {
        return $this->belongsToMany(ChallengeModel::class, 'student_challenge', 'student_id', 'challenge_id')->withPivot('completed');
    }

    public function points()
    {
        return $this->hasMany(PointModel::class, 'student_id');
    }

    public function rewardPurchases()
    {
        return $this->hasMany(RewardPurchaseModel::class, 'student_id');
    }

}
