<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPurchaseModel extends Model
{
    use HasFactory;

    protected $table = 'reward_purchase';
    protected $fillable = ['student_id', 'reward_id'];


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function reward()
    {
        return $this->belongsTo(RewardModel::class, 'reward_id');
    }

}
