<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardModel extends Model
{
    use HasFactory;

    protected $table = 'reward';
    protected $fillable = ['name', 'description', 'points_price', 'code', 'valid_until'];

    public function rewardPurchases()
    {
        return $this->hasMany(RewardPurchaseModel::class, 'reward_id');
    }


}
